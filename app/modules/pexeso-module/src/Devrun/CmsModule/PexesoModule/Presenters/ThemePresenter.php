<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    GraphicsPresenter.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Presenters;

use Devrun\Application\UI\Presenter\TImgStoragePipe;
use Devrun\CmsModule\Controls\FlashMessageControl;
use Devrun\CmsModule\Controls\IThemeControlFactory;
use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Entities\PageEntity;
use Devrun\CmsModule\Entities\RouteEntity;
use Devrun\CmsModule\Facades\ThemeFacade;
use Devrun\CmsModule\Forms\IPackageSelectFormFactory;
use Devrun\CmsModule\InvalidArgumentException;
use Devrun\CmsModule\NotFoundResourceException;
use Devrun\CmsModule\Presenters\AdminPresenter;
use Devrun\CmsModule\Repositories\PackageRepository;
use Devrun\CmsModule\Repositories\RouteRepository;
use Devrun\ContestModule\Facades\ResourceManager;
use Devrun\ContestModule\Repositories\PageCaptureRepository;
use Devrun\PhantomModule\Entities\ImageEntity;
use Devrun\Utils\Arrays;

class ThemePresenter extends AdminPresenter
{
    use TImgStoragePipe;

    /** @var IPackageSelectFormFactory @inject */
    public $packageSelectFormFactory;


    /** @var IThemeControlFactory @inject */
    public $themeControlFactory;

    /** @var ResourceManager @inject */
    public $resourceManager;

    /** @var ThemeFacade @inject */
    public $themeFacade;

    /** @var PageCaptureRepository @inject */
    public $pageCaptureRepository;

    /** @var RouteRepository @inject */
    public $routeRepository;


    /** @var PackageRepository @inject */
    public $packageRepository;



    /** @var PackageEntity */
    private $packageEditEntity;



    private $packageList = [];



    public function actionEdit($id)
    {
        $resultSet = $this->packageRepository->getAllowedPackages("pexeso");
        if (!$this->packageList = $this->packageRepository->getPairs($resultSet, 'id', 'name')) {

            $message = "Nemáte přiřazen žádný balíček";
            $this->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Theme edit", FlashMessageControl::TOAST_DANGER);
            $this->ajaxRedirect(':Cms:Default:', null, ['flash']);
        }

        if (!$id) {
            $id = Arrays::array_first_key($this->packageList);
            $this->redirect('this', ['id' => $id]);
        }


        if (!$this->packageEditEntity = $this->packageRepository->find($id)) {
            throw new InvalidArgumentException("nenalezeno");
        }


    }


    /**
     * @param $uri
     *
     * @return ImageEntity
     */
    public function getImage($uri)
    {
        if ($route = $this->routeRepository->findOneBy([
            'uri' => $uri,
            'package.module' => $module = $this->packageEditEntity->getModule(),
            'package.name' => $package = $this->packageEditEntity->getName()]
        )) {

            $image = $this->pageCaptureRepository->getRouteImage($route);
            return $image;
        }

        $route = new RouteEntity(new PageEntity('unknown', 'unknown', 'unknown', $this->translator), $this->translator);
        return (new ImageEntity($route))->setIdentifier('photo/image/empty.jpg');

        throw new NotFoundResourceException("route $uri + $module, package $package not found");
    }






    protected function createComponentThemeControl($name)
    {
        $control = $this->themeControlFactory->create();

        $control
            ->setPackageEntity($this->packageEditEntity)
            ->onSuccess[] = function ($packageEntity, $css) {

            $this->packageRepository->getEntityManager()->persist($packageEntity)->flush();

//            dump($css);
//            dump($values);
//            dump($this->settingEntity);
//            die();

            $message = "Grafiké téma `<strong>{$packageEntity->name}</strong>` upraveno <br>";
            $this->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Změna nastavení", FlashMessageControl::TOAST_SUCCESS);
            $this->redrawControl('flash');


            $this->ajaxRedirect('this', null, 'flash');

        };

        return $control;
    }



    protected function createComponentPackageSelectForm($name)
    {
        $form = $this->packageSelectFormFactory->create();
        $form->setPackages($this->packageList);
//        $form->bindEntity($packages);

        $form->create();
        $form->bootstrap3Render();
        $form->setDefaults([
            'package' => $this->request->getParameter('id'),
        ]);
        $form->onSuccess[] = function ($form, $values) {

            if ($this->isAjax()) {
                $this->ajaxRedirect('this', 'themeControl');

            } else {
                $this->redirect('this', ['id' => $values->package]);
            }


        };

        return $form;
    }


}