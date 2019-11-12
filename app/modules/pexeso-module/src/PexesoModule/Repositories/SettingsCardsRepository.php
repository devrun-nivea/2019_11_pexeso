<?php
/**
 * This file is part of devrun-souteze.
 * Copyright (c) 2018
 *
 * @file    SettingsCardsRepository.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Repositories;

use Devrun\CmsModule\Entities\ImagesEntity;
use Devrun\CmsModule\Repositories\ImageRepository;
use Kdyby\Doctrine\EntityRepository;
use PexesoModule\Entities\PexesoSettingsCardsEntity;

class SettingsCardsRepository extends EntityRepository
{

    /** @var ImageRepository @inject */
    public $imageRepository;


    /**
     * @param int $cardsOnPage
     *
     * @return mixed
     */
    public function findRandomActiveCards($cardsOnPage = 10, $package = null)
    {
        $cardsCount = $this->countBy(['active' => true, 'package' => $package]);

        if ($cardsOnPage > $cardsCount) {
            $cardsOnPage = $cardsCount;
        }

        return $this->createQueryBuilder('e')
            ->addSelect('t')
            ->leftJoin('e.translations', 't')
            ->where('e.active = true')
            ->andWhere('e.package = :package')->setParameter('package', $package)
            ->orderBy('e.name', 'ASC')
            ->setMaxResults($cardsOnPage)
            ->setFirstResult(rand(0,$cardsCount-$cardsOnPage))
            ->getQuery()
            ->getResult();
    }


    /**
     * @param PexesoSettingsCardsEntity $cardsEntity
     *
     * @return ImagesEntity[]
     */
    public function getExistCardImages(PexesoSettingsCardsEntity $cardsEntity)
    {
        $images = $this->getCardImageList($cardsEntity);
        $result = [];
        foreach ($images as $image) {
            if ($imageEntity = $this->imageRepository->findOneBy(['identify.namespace' => $image['namespace'], 'identify.name' => $image['systemName'], 'route.page.module' => 'pexeso', 'route.package' => $cardsEntity->getPackage()])) {
                $result[$image['name']] = $imageEntity;
            }
        }

        return $result;
    }


    /**
     * Return image list of one card setting
     *
     * @param PexesoSettingsCardsEntity $cardsEntity
     *
     * @return array
     */
    public function getCardImageList(PexesoSettingsCardsEntity $cardsEntity)
    {
        return [
            ['name' => 'front1', 'namespace' => 'images/pexeso/cards', 'systemName' => "{$cardsEntity->getName()}1-front"],
            ['name' => 'back1', 'namespace' => 'images/pexeso/cards', 'systemName' => "{$cardsEntity->getName()}1-back"],
            ['name' => 'front2', 'namespace' => 'images/pexeso/cards', 'systemName' => "{$cardsEntity->getName()}2-front"],
            ['name' => 'back2', 'namespace' => 'images/pexeso/cards', 'systemName' => "{$cardsEntity->getName()}2-back"],
            ['name' => 'benefit', 'namespace' => 'images/pexeso/benefits', 'systemName' => "benefit-{$cardsEntity->getName()}"],
        ];

    }


}