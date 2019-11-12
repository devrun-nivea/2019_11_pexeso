<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    SettignsCardsControl.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Controls;

use Devrun\CmsModule\Controls\AdminControl;
use Devrun\CmsModule\Controls\DataGrid;
use Devrun\CmsModule\Controls\FlashMessageControl;
use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Presenters\PagePresenter;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Utils\Html;
use Nette\Utils\Validators;
use PexesoModule\Entities\PexesoSettingsCardsEntity;
use PexesoModule\Repositories\SettingsCardsRepository;
use Tracy\Debugger;

interface ISettingsCardsControlFactory
{
    /** @return SettingsCardsControl */
    function create();
}

class SettingsCardsControl extends AdminControl
{

    /** @var SettingsCardsRepository @inject */
    public $settingsCardsRepository;

    /** @var PackageEntity */
    private $package;


    protected function attached($presenter)
    {
        if ($presenter instanceof PagePresenter) {
            $this->package = $presenter->getRouteEntity()->getPackage();
        }

        parent::attached($presenter);
    }



    public function render()
    {
        $template = $this->createTemplate();
        $template->render();
    }


    /**
     * @deprecated
     *
     * @param $id
     */
    public function handleDelete($id)
    {
        /** @var PexesoSettingsCardsEntity $entity */
        if (!$entity = $this->settingsCardsRepository->find($id)) {
            $this->flashMessage('Záznam nenalezen', 'danger');
            $this->ajaxRedirect();
        }

        $this->settingsCardsRepository->getEntityManager()->remove($entity)->flush();
        $this->flashMessage("Záznam {$entity->getId()} smazán", 'success');

        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flash');
            $this['cardsControlGrid']->reload();
        }

        $this->ajaxRedirect();
    }


    public function handleRedraw()
    {
        $this['cardsControlGrid']->reload();
        $this->pageRedraw();
    }





    protected function createComponentCardsControlGrid($name)
    {
        $grid = new DataGrid();
        $grid->setTranslator($this->translator);
        $grid->setRefreshUrl(false);
        $grid->setRememberState(false);


        $model = $this->settingsCardsRepository->createQueryBuilder('e')
            ->addSelect('t')
            ->join('e.package', 'p')
            ->leftJoin('e.translations', 't');


        if ($this->package === null) {
            $model->andWhere('e.package IS NULL');

        } else {
            $model->andWhere('e.package = :package')->setParameter('package', $this->package);
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
            ->setEditableInputType('text', ['class' => 'form-control'])
            ->setEditableCallback(function($id, $value) use ($grid) {
                if (Validators::is($value, 'string:4..64')) {
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

        $statusList = array(null => 'Všechny', '0' => 'Neaktivní', '1' => 'Aktivní');

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

        /** @var PagePresenter $presenter */
        $presenter = $this->presenter;

        $grid->getColumn('active')
            ->onChange[] = function ($id, $new_value) use ($grid, $presenter) {

            /** @var SettingsCardsEntity $entity */
            $entity = $this->settingsCardsRepository->find($id);
            $entity->setActive($new_value);
            $this->settingsCardsRepository->getEntityManager()->persist($entity)->flush();

            $message = $entity->active
                ? "Karta `{$entity->name}` aktivní"
                : "Karta `{$entity->name}` neaktivní";

            $this->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_INFO);
            $this->redrawControl('flash');
            $grid->redrawItem($id);
//            $this->ajaxRedirect('this', null, ['devices', 'flash']);
        };

        $grid->addAction('detail', 'Edit detail', ':Cms:Pexeso:Cards:edit')
            ->setRenderer(function (PexesoSettingsCardsEntity $entity) {
                $html = Html::el("a")
                    ->addText(" Edit detail")
                    ->setAttribute('class', 'btn btn-xs btn-default')
                    ->setAttribute('data-modal-dialog', 'modal-primary')
                    ->setAttribute('data-modal-success', $this->link('redraw!', ['id' => $entity->getId()]))
                    ->setAttribute('data-modal-title', "Úprava karty {$entity->getName()}")
                    ->setAttribute('data-modal-type', 'modal-full')
//                    ->setAttribute('data-modal-autoclose', 'true')
                    ->href($this->presenter->link(":Cms:Pexeso:Cards:edit", ['id' => $entity->getId()]));

                $html->insert(0, Html::el('span')->setAttribute('class', 'fa fa-edit fa-2x'));
                return $html;
            });

//            ->setIcon('edit fa-2x')
//            ->setClass('_ajax btn btn-xs btn-primary')
//            ->setDataAttribute('popup-dialog', 'popup')
//            ->setDataAttribute('popup-title', 'Úprava nastavení karty')
//            ->setDataAttribute('modal-success', $this->link('redraw!'))
//            ->setDataAttribute('auto-close', true)
//            ->setDataAttribute('popup-type', 'modal-full');

//        dump($this->getParameterId($name));



        /**
         * Save update values
         */
        $grid->getInlineEdit()->onSubmit[] = function($id, $values) use ($presenter) {

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

            try {
                $this->em->persist($entity)->flush();

            } catch (UniqueConstraintViolationException $e) {
                $message = "Asddd";
                $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_INFO);
                $presenter->redrawControl('flash');

            }


            return true;
        };



        return $grid;
    }



}