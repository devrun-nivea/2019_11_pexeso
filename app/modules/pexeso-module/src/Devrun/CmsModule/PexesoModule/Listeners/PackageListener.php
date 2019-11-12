<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    CopyPackageListener.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Listeners;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Facades\PackageFacade;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;
use PexesoModule\Entities\PexesoSettingsCardsEntity;
use PexesoModule\Entities\PexesoSettingsEntity;

class PackageListener implements Subscriber
{

    /** @var EntityManager */
    private $em;

    /**
     * CopyPackageListener constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param PackageEntity      $newPackage
     * @param PackageEntity|null $oldPackage
     *
     * @return bool
     */
    private function copySettings(PackageEntity $newPackage, PackageEntity $oldPackage = null)
    {
        /** @var PexesoSettingsEntity $oldSettings */
        if ($oldSettings = $this->em->getRepository(PexesoSettingsEntity::class)->findOneBy(['package' => $oldPackage])) {

            $newSettingsEntity = clone $oldSettings;
            $newSettingsEntity->setPackage($newPackage);

            $this->em->persist($newSettingsEntity);
            return true;
        }

        return false;
    }


    /**
     * @param PackageEntity      $newPackage
     * @param PackageEntity|null $oldPackage
     *
     * @return bool
     */
    private function copyCardsSettings(PackageEntity $newPackage, PackageEntity $oldPackage = null)
    {
        $query = $this->em->createQueryBuilder()
            ->select('e')
            ->addSelect('t')
            ->from(PexesoSettingsCardsEntity::class, 'e')
            ->join('e.translations', 't');

        if ($oldPackage) {
            $query->andWhere('e.package = :package')->setParameter('package', $oldPackage);

        } else {
            $query->andWhere('e.package IS NULL');
        };

        /** @var PexesoSettingsCardsEntity[] $settingsCards */
        $settingsCards = $query
            ->getQuery()
            ->getResult();


        if ($settingsCards) {

            foreach ($settingsCards as $settingsCard) {
                $oldCardsTranslations  = $settingsCard->getTranslations();
                $newSettingsCardEntity = clone $settingsCard;
                $newSettingsCardEntity->setPackage($newPackage);

                foreach ($oldCardsTranslations as $oldCardsTranslation) {
                    $newCardTranslationEntity = clone $oldCardsTranslation;
                    $newSettingsCardEntity->addTranslation($newCardTranslationEntity);
                }


                $this->em->persist($newSettingsCardEntity);
            }

            return true;
        }

        return false;
    }


    /**
     * CopyPackage listener
     *
     * @param PackageEntity      $newPackage
     * @param PackageEntity|null $oldPackage
     */
    public function onCopyPackage(PackageEntity $newPackage, PackageEntity $oldPackage = null)
    {
        if ($newPackage->getModule() != 'pexeso') return;

        $readyToFlush = false;

        if ($this->copySettings($newPackage, $oldPackage)) $readyToFlush = true;
        if ($this->copyCardsSettings($newPackage, $oldPackage)) $readyToFlush = true;

        /*
         * flush if needed
         */
        if ($readyToFlush) {
//            $this->em->flush();
        }
    }


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            PackageFacade::EVENT_COPY_PACKAGE
        ];
    }


}