<?php
/**
 * This file is part of devrun-souteze.
 * Copyright (c) 2018
 *
 * @file    SettingsPexeso.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Entities;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\Doctrine\Entities\Attributes\Translatable;
use Devrun\Doctrine\Entities\BlameableTrait;
use Devrun\Doctrine\Entities\DateTimeTrait;
use Devrun\Doctrine\Entities\IdentifiedEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\MagicAccessors;
use Kdyby\Translation\ITranslator;
use Kdyby\Translation\Translator;
use Nette\Utils\Strings;


/**
 * Class SettingsCardsEntity
 *
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\Entity(repositoryClass="PexesoModule\Repositories\SettingsCardsRepository")
 * @ORM\Table(name="pexeso_settings_cards",  uniqueConstraints={@ORM\UniqueConstraint(
 *    name="cards_name_package_idx", columns={"name", "package_id"}
 * )})
 *
 * @package PexesoModule\Entities
// * @method PexesoSettingsCardsTranslation translate($lang='')
 * @method setActive($active)
 * @method getName()
 */
class PexesoSettingsCardsEntity
{

    use IdentifiedEntityTrait;
    use MagicAccessors;
    use DateTimeTrait;
    use BlameableTrait;
    use Translatable;

    /**
     * @var PackageEntity
     * @ORM\ManyToOne(targetEntity="Devrun\CmsModule\Entities\PackageEntity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $package;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gtmName;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $active = true;

    /**
     * PexesoSettingsCardsEntity constructor.
     *
     * @param ITranslator|Translator $translator
     */
    public function __construct(ITranslator $translator)
    {
//        $this->setDefaultLocale($translator->getDefaultLocale());
//        $this->setCurrentLocale($translator->getLocale());

    }


    /**
     * @param string $benefitName
     */
    public function setBenefitName($benefitName)
    {
        $this->benefitName = trim(Strings::webalize($benefitName, '_'));
    }

    /**
     * @param PackageEntity $package
     *
     * @return $this
     */
    public function setPackage(PackageEntity $package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * @return PackageEntity
     */
    public function getPackage(): PackageEntity
    {
        return $this->package;
    }





    /*
     * ***************************************************************************************
     * translation properties
     * ***************************************************************************************
     *
     */

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setHeader($name)
    {
        $this->translate($this->currentLocale, false)->header = $name;
        return $this;
    }

    public function getHeader()
    {
        return $this->translate()->header;
    }


    /**
     * @param $name
     *
     * @return $this
     */
    public function setDescription($name)
    {
        $this->translate($this->currentLocale, false)->description = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->translate()->description;
    }




    /*
     * ***************************************************************************************
     * internal properties
     * ***************************************************************************************
     *
     */

    public function __clone()
    {
        $this->id = NULL;
        $this->translations = [];
    }

}