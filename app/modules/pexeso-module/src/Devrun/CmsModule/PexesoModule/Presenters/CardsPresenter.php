<?php
/**
 * This file is part of pexeso-devrun.
 * Copyright (c) 2018
 *
 * @file    CardsPresenter.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Presenters;

use Devrun\Application\UI\Presenter\TImgStoragePipe;
use Devrun\CmsModule\Administration\Controls\IRawImagesControlFactory;
use Devrun\CmsModule\Administration\Controls\ISettingsControlFactory;
use Devrun\CmsModule\Controls\DataGrid;
use Devrun\CmsModule\Controls\FlashMessageControl;
use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Entities\RouteEntity;
use Devrun\CmsModule\Facades\ImageManageFacade;
use Devrun\CmsModule\Forms\DevrunForm;
use Devrun\CmsModule\InvalidStateException;
use Devrun\CmsModule\PexesoModule\Forms\ICardFormFactory;
use Devrun\CmsModule\Presenters\AdminPresenter;
use Devrun\CmsModule\Repositories\PackageRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Http\FileUpload;
use Nette\Utils\Html;
use Nette\Utils\Validators;
use PexesoModule\Entities\PexesoSettingsCardsEntity;
use PexesoModule\Repositories\SettingsCardsRepository;
use Tracy\Debugger;

class CardsPresenter extends AdminPresenter
{

    use TImgStoragePipe;

    /** @var IRawImagesControlFactory @inject */
    public $rawImagesControlFactory;

    /** @var ICardFormFactory @inject */
    public $cardFormFactory;


    /** @var SettingsCardsRepository @inject */
    public $settingsCardsRepository;

    /** @var ImageManageFacade @inject */
    public $imageManageFacade;

    /** @var PackageRepository @inject */
    public $packageRepository;


    /** @var ISettingsControlFactory @inject */
    public $settingsControl;



    public function handleRedrawGrid($id)
    {
        $this['cardsControlGrid']->redrawItem($id);
    }


    public function handleDelete($id)
    {
        /** @var PexesoSettingsCardsEntity $entity */
        if (!$entity = $this->settingsCardsRepository->find($id)) {
            $this->flashMessage('Záznam nenalezen', FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_WARNING);
            $this->ajaxRedirect();
        }

        $images = $this->settingsCardsRepository->getExistCardImages($entity);
        foreach ($images as $image) {
            $this->imageManageFacade->getPageImageJob()->removeImageByID($image->getId());
        }

        $this->settingsCardsRepository->getEntityManager()->remove($entity)->flush();
        $this->flashMessage("Záznam {$entity->getId()} smazán", FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_SUCCESS);

        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flash');
            $this['cardsControlGrid']->reload();
        }

        $this->ajaxRedirect();
    }


    public function actionEdit($id)
    {

        /** @var PexesoSettingsCardsEntity $entity */
        if (!$entity = $this->settingsCardsRepository->find($id)) {
            dump($this);
            die();

        }

        $images = $this->settingsCardsRepository->getCardImageList($entity);

        $imageEntities = [];
        foreach ($images as $image) {

            if (!$imageEntity = $this->imageManageFacade->imageRepository->findOneBy(['identify.name' => $image['systemName'], 'route.page.module' => 'pexeso', 'route.package' => $entity->getPackage()])) {

                $route = $this->settingsCardsRepository->getEntityManager()->getRepository(RouteEntity::class)->findOneBy([
                    'package' => $entity->getPackage(),
                    'page.module' => 'pexeso',
                ]);

                if ($route) {
                    $emptyImage = $this->imageManageFacade->generateEmptyImage($image['systemName']);

                    if (!$imageEntity = $this->imageManageFacade->getPageImageJob()->createImageFromImage($emptyImage, $image['namespace'] . '/' . $image['systemName'] . '.png' )) {
                        throw new InvalidStateException('Empty Image not created');
                    }

                    $imageEntity->setRoute($route);

                    $this->settingsCardsRepository->getEntityManager()->persist($imageEntity)->flush();
                }
            }

            $imageEntities[$image['name']] = $imageEntity;
        }

        $this->template->imageEntities = $imageEntities;
        $this->template->entity = $entity;
    }



    protected function createComponentSettingsControl()
    {
        $control = $this->settingsControl->create();
        $control
            ->setInBox(false)
            ->setKeys(['pexeso_cards',]);

        return $control;
    }


    protected function createComponentCardsControlGrid($name)
    {
        $grid = new DataGrid();
        $grid->setTranslator($this->translator)
            ->setRefreshUrl(false);

        $model = $this->settingsCardsRepository->createQueryBuilder('e')
            ->addSelect('t')
            ->join('e.package', 'p')
            ->leftJoin('e.translations', 't');

        if (!$this->user->isAllowed("Cms:Page", 'editAllPackages')) {
            $model->andWhere('p.user = :user')->setParameter('user', $this->user->id);
        }


        $grid->setDataSource($model);

        $grid->addColumnText('name', 'Jméno')
            ->setFitContent()
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('gtmName', 'GTM Jméno')
            ->setEditableCallback(function($id, $value) use ($grid) {
                if (Validators::is($value, $validate = 'string:4..32')) {
                    if ($entity = $this->settingsCardsRepository->find($id)) {
                        $entity->gtmName = $value;
                        $this->settingsCardsRepository->getEntityManager()->persist($entity)->flush();
                        return true;
                    }
                }
                $message = "input not valid [$value != $validate]";
                return $grid->invalidResponse($message);
            })
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('header', 'Header')
            ->setSortable()
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->addOrderBy('t.header', $value['header']);
            })
            ->setEditableInputType('text', ['class' => 'form-control'])
            ->setEditableCallback(function($id, $value) use ($grid) {
                if (Validators::is($value, 'string:4..128')) {
                    /** @var PexesoSettingsCardsEntity $entity */
                    if ($entity = $this->settingsCardsRepository->find($id)) {
                        $entity->header = ($value);
                        $this->settingsCardsRepository->getEntityManager()->persist($entity);
                        $entity->mergeNewTranslations();
                        $this->settingsCardsRepository->getEntityManager()->flush();
                        return true;
                    }
                }

                $message = "input not valid";
                return $grid->invalidResponse($message);
            })
            ->setFilterText()
            ->setCondition(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->andWhere('t.header LIKE :like')->setParameter('like', "%$value%");
            });

        $grid->addColumnText('description', 'Popis')
            ->setSortable()
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->addOrderBy('t.description', $value['description']);
            })
            ->setEditableInputType('textarea', ['class' => 'form-control', 'rows' => 4])
            ->setEditableCallback(function($id, $value) use ($grid) {
                if (Validators::is($value, 'string:4..255')) {
                    /** @var PexesoSettingsCardsEntity $entity */
                    if ($entity = $this->settingsCardsRepository->find($id)) {
                        $entity->setDescription($value);
                        $this->settingsCardsRepository->getEntityManager()->persist($entity);
                        $entity->mergeNewTranslations();
                        $this->settingsCardsRepository->getEntityManager()->flush();
                        return true;
                    }
                }

                $message = "input not valid";
                return $grid->invalidResponse($message);
            })

            ->setFilterText()
            ->setCondition(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->andWhere('t.description LIKE :like')->setParameter('like', "%$value%");
            });



        $packages = ['' => 'Všechny'];
        /** @var PackageEntity[] $rawPackages */
        $rawPackages = $this->packageRepository->getAllowedPackages('pexeso');
        foreach ($rawPackages as $packageEntity) {
            $packages[$packageEntity->getId()] = $packageEntity->getName();
        }

        $grid->addColumnText('package', 'Pack', 'package.name')
            ->setSortable()
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->addOrderBy('p.name', $value['package.name']);
            })
            ->setFilterSelect($packages)
            ->setCondition(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->andWhere('p.id =:package')->setParameter('package', $value);
            });






        /* @todo history reason, translate from neon
        $grid->addColumnText('localeValue', 'Popis benefitu (html nadpis \n small)')
            ->setRenderer(function ($row) {
                $localValue = $this->translator->domain($domain = 'pexeso')->translate($translateId = "results.{$row->name}");

                $result = Html::el('p')
                    ->setHtml($localValue)
                    ->setAttribute('contenteditable', 'true')
                    ->setAttribute('data-domain', $domain)
                    ->setAttribute('data-translate', $translateId);
                return $result;
            });
        */




        $statusList = array('' => 'Všechny', '0' => 'Neaktivní', '1' => 'Aktivní');

        $grid->addColumnStatus('active', 'Stav')
            ->setFitContent()
            ->addOption(0, 'Neaktivní')
            ->setClass('btn-default')
            ->endOption()

            ->addOption(1, 'Aktivní')
            ->setClass('btn-success')
            ->endOption()
            ->setSortable()
            ->setFilterSelect($statusList);

        $presenter = $this;

        $grid->getColumn('active')
            ->onChange[] = function ($id, $new_value) use ($grid, $presenter) {

            /** @var PexesoSettingsCardsEntity $entity */
            $entity = $this->settingsCardsRepository->find($id);
            $entity->setActive($new_value);
            $this->settingsCardsRepository->getEntityManager()->persist($entity)->flush();

            $message = $entity->active
                ? "Karta `{$entity->getName()}` aktivní"
                : "Karta `{$entity->getName()}` neaktivní";

            $this->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_INFO);
            $this->redrawControl('flash');
            $grid->redrawItem($id);
        };


        $grid->addAction('edit', 'Edit')
            ->setIcon('edit fa-2x')
            ->setClass('_ajax btn btn-xs btn-primary')
            ->setRenderer(function (PexesoSettingsCardsEntity $article) {

                $html = Html::el("a")
                    ->addText(" Edit detail")
                    ->setAttribute('class', 'btn btn-xs btn-default')
                    ->setAttribute('data-modal-dialog', 'modal-default')
                    ->setAttribute('data-modal-success', $this->link('redrawGrid!', ['id' => $article->getId()]))
                    ->setAttribute('data-modal-title', 'Úprava nastavení karty')
                    ->setAttribute('data-modal-type', 'modal-lg')
                    ->setAttribute('data-modal-autoclose', 'true')
                    ->href($this->presenter->link("edit", ['id' => $article->getId()]));

                $html->insert(0, Html::el('span')->setAttribute('class', 'fa fa-edit fa-2x'));
                return $html;
            });


        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setIcon('trash fa-2x')
            ->setClass('_ajax btn btn-xs btn-danger')
            ->setConfirm(function ($item) {
                return "Opravdu chcete smazat záznam [id: {$item->id}; `{$item->name}`]?";
            });


        $grid->addInlineAdd()
            ->onControlAdd[] = function (Container $container) use ($packages) {
            unset($packages['']);
            $container->addText('name', '')->addRule(Form::FILLED)->setDefaultValue('a')->setAttribute('placeholder', 'card-name');
            $container->addText('gtmName', '');
            $container->addText('header', '');
            $container->addText('description', '');
            $container->addSelect('package', "Pack", $packages);
        };

        $p = $presenter;

        $grid->getInlineAdd()->onSubmit[] = function($values) use ($p, $grid) {
            /**
             * Save new values
             */
            $entity = new PexesoSettingsCardsEntity($this->translator);

            $ignore = ['package'];
            foreach ($values as $key => $value) {
                if (!in_array($key, $ignore)) $entity->$key = $value;
            }

            /** @var PackageEntity $packageEntity */
            if ($packageEntity = $this->packageRepository->find($values->package)) {
                $entity->setPackage($packageEntity);
            }

            try {
                $this->settingsCardsRepository->getEntityManager()->persist($entity)->flush();

                /**
                 * Save new values
                 */
                $this->flashMessage('Záznam upraven', FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_SUCCESS);

            } catch (UniqueConstraintViolationException $exception) {
                $this->flashMessage('Záznam se nepodařilo vytvořit, přesvědčte se prosím o správném názvu karty, musí být jedinečný', FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_DANGER);

            }

            $grid->reload();
            $p->ajaxRedirect('default', [], ['flash']);
        };


        $grid->getInlineEdit()->onSubmit[] = function($id, $values) {

            if (!$entity = $this->settingsCardsRepository->find($id)) {
                $this->flashMessage('Record was not found', 'danger');
                $this->redrawControl('flash');
                return false;

            } else {
                foreach ($values as $key => $value) {
                    $entity->$key = $value;
                }
            }

            /**
             * Save new values
             */

            $message = $entity->active
                ? "Karta `{$entity->name}` aktivní"
                : "Karta `{$entity->name}` neaktivní";

            $this->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_INFO);
            $this->redrawControl('flash');

            $this->flashMessage('Record was updated!', 'success');
            $this->redrawControl('flash');
            $this->em->persist($entity)->flush();

            return true;
        };



        return $grid;
    }


    protected function createComponentCardForm($name)
    {
        $form   = $this->cardFormFactory->create();

        /** @var PexesoSettingsCardsEntity $entity */
        $entity = $this->template->entity;

        $form->create();
        $form->bindEntity($entity);
        $form->setDefaults([
            'header' => $entity->getHeader(),
            'description' => $entity->getDescription(),
        ]);
        $form->bootstrap3Render();

        $form->onSuccess[] = function (DevrunForm $form, $values) {

            /** @var PexesoSettingsCardsEntity $entity */
            $entity = $form->getEntity();

            $entity->setHeader($values->header);
            $entity->setDescription($values->description);
            $entity->mergeNewTranslations();

            foreach ($images = ['front1', 'front2', 'back1', 'back2', 'benefit',] as $imageName) {
                /** @var FileUpload $image */
                $image  = $values->$imageName;

                if ($image->isOk()) {
                    $imageEntity = $this->template->imageEntities[$imageName];
                    $this->imageManageFacade->getPageImageJob()->updateImageFromUpload($imageEntity, $image);
                }
            }

            $this->settingsCardsRepository->getEntityManager()->persist($entity)->flush();

            $title = "Nastavení pexeso karty";
            $this->flashMessage("Karta {$entity->getName()} upravena", FlashMessageControl::TOAST_TYPE, $title, FlashMessageControl::TOAST_SUCCESS);
            $this->ajaxRedirect('this', null, ['flash', 'form']);
        };

        return $form;
    }

}