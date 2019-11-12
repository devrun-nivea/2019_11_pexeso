<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    PexesoSettingsCardsTranslationEntity.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Entities;

use Devrun\Doctrine\Entities\Attributes\Translation;
use Kdyby\Doctrine\Entities\MagicAccessors;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PexesoSettingsCardsTranslationEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="pexeso_settings_cards_translation")
 *
 * @package PexesoModule\Entities
 */
class PexesoSettingsCardsTranslationEntity
{

    use MagicAccessors;
    use Translation;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $header = '';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $description = '';




    public function __clone()
    {
        $this->id = NULL;
        $this->translatable = null;
    }

}