<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    ModuleListener.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Listeners;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Entities\RouteEntity;
use Devrun\CmsModule\Facades\PackageFacade;
use Devrun\CmsModule\InvalidStateException;
use Devrun\CmsModule\Repositories\PackageRepository;
use Devrun\Module\ModuleFacade;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;
use PexesoModule\Entities\PexesoSettingsEntity;

class ModuleListener implements Subscriber
{

    /** @var EntityManager */
    private $em;

    /** @var PackageRepository */
    private $packageRepository;

    /** @var PackageFacade */
    private $packageFacade;

    /**
     * ModuleListener constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, PackageFacade $packageFacade, PackageRepository $packageRepository)
    {
        $this->em = $em;
        $this->packageFacade = $packageFacade;
        $this->packageRepository = $packageRepository;
    }


    /**
     * @param ModuleFacade $moduleFacade
     * @param string       $module
     */
    public function onUpdate(ModuleFacade $moduleFacade, $module)
    {
        $thisModule = 'pexeso';

        if ($module == $thisModule) {

            if (!$packageEntity = $this->packageRepository->findOneBy(['module' => $thisModule, 'name' => \ContestModule\Listeners\ModuleListener::DEFAULT_PACKAGE])) {
                throw new InvalidStateException("Default package for pexeso not found");
            }

            if (!$settingsEntity = $this->em->getRepository(PexesoSettingsEntity::class)->findOneBy(['package' => $packageEntity])) {
                $settingsEntity = new PexesoSettingsEntity();
                $settingsEntity->setPackage($packageEntity);

                $this->em->persist($settingsEntity)->flush();
            }

        }
    }





    function getSubscribedEvents()
    {
        /**
         * @todo deprecated
         */
        return [
//            'Devrun\Module\ModuleFacade::onUpdate'
        ];
    }
}