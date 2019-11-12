<?php
/**
 * This file is part of the 2017_09_pexeso
 * Copyright (c) 2015
 *
 * @file    QuestionEntity.php
 * @author  Pavel Paulík <pavel.paulik1@gmail.com>
 */

namespace PexesoModule\Entities;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\Doctrine\Entities\BlameableTrait;
use Devrun\Doctrine\Entities\DateTimeTrait;
use Devrun\Doctrine\Entities\IdentifiedEntityTrait;
use Devrun\Doctrine\Entities\UserEntity;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;
use Nette\Object;

/**
 * Class QuestionEntity
 *
 * @ORM\Entity(repositoryClass="PexesoModule\Repositories\ResultRepository")
 * @ORM\Table(name="result_pexeso")
 */
class ResultEntity extends Object
{
    use MagicAccessors;
    use IdentifiedEntityTrait;
    use DateTimeTrait;
    use BlameableTrait;


    /**
     * @var PackageEntity
     * @ORM\ManyToOne(targetEntity="Devrun\CmsModule\Entities\PackageEntity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $package;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quizOne;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quizTwo;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quizThree;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quizFour;


    /**
     * QuestionEntity constructor.
     */
    public function __construct(UserEntity $userEntity = null, PackageEntity $packageEntity = null)
    {
        $this->createdBy = $userEntity;
        $this->package = $packageEntity;
    }


    /**
     * @param string $quizFour
     *
     * @return $this
     */
    public function setQuizFour($quizFour)
    {
        $this->quizFour = $quizFour;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuizFour()
    {
        return $this->quizFour;
    }

    /**
     * @param string $quizOne
     *
     * @return $this
     */
    public function setQuizOne($quizOne)
    {
        $this->quizOne = $quizOne;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuizOne()
    {
        return $this->quizOne;
    }

    /**
     * @param string $quizTree
     *
     * @return $this
     */
    public function setQuizThree($quizTree)
    {
        $this->quizThree = $quizTree;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuizThree()
    {
        return $this->quizThree;
    }

    /**
     * @param string $quizTwo
     *
     * @return $this
     */
    public function setQuizTwo($quizTwo)
    {
        $this->quizTwo = $quizTwo;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuizTwo()
    {
        return $this->quizTwo;
    }





}