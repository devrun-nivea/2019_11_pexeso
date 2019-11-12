<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    PexesoSettingsEntity.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Entities;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\Doctrine\Entities\IdentifiedEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;

/**
 * Class PexesoSettings
 *
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\Entity(repositoryClass="PexesoModule\Repositories\PexesoSettingsRepository")
 * @ORM\Table(name="pexeso_settings")
 *
 * @package PexesoModule\Entities
 */
class PexesoSettingsEntity
{

    use IdentifiedEntityTrait;
    use MagicAccessors;


    /**
     * @var PackageEntity
     * @ORM\ManyToOne(targetEntity="Devrun\CmsModule\Entities\PackageEntity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $package;


    /**
     * @var string
     * @ORM\Column(type="smallint")
     */
    protected $cards = 10;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $formRedirect = true;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $thanksRedirect = true;


    /**
     * @return PackageEntity
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param PackageEntity $package
     *
     * @return $this
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }


    /**
     * @param array $theme
     *
     * @return $this
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }


    /**
     * @return array
     */
    public function getTheme()
    {
        return $this->theme;
    }





}