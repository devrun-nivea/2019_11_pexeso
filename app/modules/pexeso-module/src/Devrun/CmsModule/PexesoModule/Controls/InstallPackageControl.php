<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    InstallPackageControl.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Controls;

use Devrun\Application\UI\Control\Control;
use Devrun\CmsModule\Controls\FlashMessageControl;
use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Entities\RouteEntity;
use Devrun\CmsModule\Entities\RouteTranslationEntity;
use Devrun\CmsModule\Facades\PackageFacade;
use Devrun\CmsModule\Forms\IDevrunForm;
use Devrun\CmsModule\Forms\IPackageSelectFormFactory;
use Devrun\CmsModule\Presenters\AdminPresenter;
use Devrun\CmsModule\Repositories\PackageRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Application\UI\Form;

interface IInstallPackageControlFactory {

    /**
     * @return InstallPackageControl
     */
    public function create();
}

class InstallPackageControl extends Control
{


    /** @var IDevrunForm @inject */
    public $devrunFormFactory;

    /** @var PackageRepository @inject */
    public $packageRepository;



    /** @var PackageFacade @inject */
    public $packageFacade;


    public function handleUninstall($package)
    {

        if ($newPackage = $this->packageRepository->findOneBy(['name' => 'Marama2'])) {
            $this->packageRepository->getEntityManager()->remove($newPackage)->flush();
        }

        /** @var AdminPresenter $presenter */
        $presenter = $this->presenter;

        $message = "balíček $package odinstalován";
        $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Package add", FlashMessageControl::TOAST_SUCCESS);


        $presenter->ajaxRedirect('this', null, ['flash']);

    }



    public function handleInstall($package)
    {
        /** @var AdminPresenter $presenter */
        $presenter = $this->presenter;
        $em = $this->packageRepository->getEntityManager();


        /**
         * custom modify after translate url
         *
         * @param $module
         * @param RouteEntity $route
         */
        $this->packageFacade->onAfterCopyPackageRoute[] = function ($module, $route) {

            /** @var RouteTranslationEntity $translationEntity */
            foreach ($route->getTranslations() as $translationEntity) {

            }
        };


        if (!$newPackage = $this->packageRepository->findOneBy(['name' => 'Marama2'])) {
            $newPackage = new PackageEntity("Marama2", "pexeso", $this->translator);
        }
        $newPackage = new PackageEntity("Marama2", "pexeso", $this->translator);

        $oldPackage = $this->packageRepository->findOneBy(['name' => 'Default', 'module' => 'pexeso']);


        try {
            $this->packageFacade->copyPackage($newPackage, $oldPackage   );



        } catch (UniqueConstraintViolationException $exception) {

            dump($exception);
            die();


            $message = "balíček `$newPackage`` se nepodařilo přidat, tento název již existuje";
            $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Package add", FlashMessageControl::TOAST_WARNING);

            $presenter->ajaxRedirect('this', null, ['flash']);
        }


        /*
         * custom debug stop add package
         */
//        die("ASD");


        $message = "balíček $package přidán";
        $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, "Package add", FlashMessageControl::TOAST_SUCCESS);


        $presenter->ajaxRedirect('this', null, ['flash']);
    }



    public function render($params = array())
    {
        dump($params);

        parent::render(); // TODO: Change the autogenerated stub
    }


    protected function createComponentPackageForm($name)
    {
        $form = $this->devrunFormFactory->create();
        $form->addText('package', 'Název balíčku')
            ->addRule(Form::FILLED)
            ->addRule(Form::MAX_LENGTH, null, 64);

        $form->addSubmit('send', 'Vytvořit');

        $form->bindEntity(new PackageEntity('nic', 'nic', $this->translator));
        $form->bootstrap3Render();
        $form->onSuccess[] = function ($form, $values) {

            $this->redirect('install!', ['package' => $values->package]);
        };


        return $form;
    }




}