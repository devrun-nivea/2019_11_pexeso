<?php
/**
 * This file is part of devrun-souteze.
 * Copyright (c) 2018
 *
 * @file    QuestionFacade.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Facades;


use Kdyby\Doctrine\EntityManager;
use PexesoModule\Entities\QuestionEntity;
use PexesoModule\Entities\ResultEntity;
use PexesoModule\Repositories\QuestionRepository;
use PexesoModule\Repositories\ResultRepository;

class QuestionFacade
{

    /** @var EntityManager */
    private $entityManager;

    /** @var ResultEntity */
    private $repository;

    /**
     * QuestionFacade constructor.
     *
     * @param ResultRepository $repository
     */
    public function __construct(ResultRepository $repository)
    {
        $this->repository = $repository;
        $this->entityManager = $repository->getEntityManager();
    }


    public function save(ResultEntity $entity)
    {
        $this->entityManager->persist($entity)->flush();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }


    /**
     * @return ResultRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }



}