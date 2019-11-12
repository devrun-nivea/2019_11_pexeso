<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    CardForm.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Forms;

use Devrun\CmsModule\Forms\DevrunForm;
use Devrun\Utils\PhpInfo;
use Nette\Application\UI\Form;

interface ICardFormFactory
{
    /** @return CardForm */
    function create();
}

class CardForm extends DevrunForm
{

    public function create()
    {
        $this->addText('name', 'Název')
            ->setAttribute('placeholder', "Název")
            ->setAttribute('readonly', true)
            ->addCondition(Form::FILLED)
            ->addRule(Form::MAX_LENGTH, NULL, 128);

        $this->addText('gtmName', 'GTM párový název')
            ->setAttribute('placeholder', "Název pro GTM")
            ->addRule(Form::FILLED)
            ->addRule(Form::MAX_LENGTH, NULL, 64);

        $this->addText('header', 'Nadpis')
            ->setAttribute('placeholder', "Zadejte nadpis benefitu")
            ->addRule(Form::FILLED)
            ->addRule(Form::MAX_LENGTH, NULL, 128);

        $this->addTextArea('description', 'Popis')
            ->setAttribute('placeholder', "Zadejte popis benefitu")
            ->addRule(Form::FILLED)
            ->addRule(Form::MAX_LENGTH, NULL, 255);

        $this->addCheckbox('active', 'Aktivní');


        $this->addUpload('front1', 'Front 1 obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE)
            ->addRule(Form::MAX_FILE_SIZE, NULL, PhpInfo::file_upload_max_size());

        $this->addUpload('back1', 'Back 1 obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE)
            ->addRule(Form::MAX_FILE_SIZE, NULL, PhpInfo::file_upload_max_size());

        $this->addUpload('front2', 'Front 2 obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE)
            ->addRule(Form::MAX_FILE_SIZE, NULL, PhpInfo::file_upload_max_size());

        $this->addUpload('back2', 'Back 2 obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE)
            ->addRule(Form::MAX_FILE_SIZE, NULL, PhpInfo::file_upload_max_size());

        $this->addUpload('benefit', 'Benefit obrázek')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE)
            ->addRule(Form::MAX_FILE_SIZE, NULL, PhpInfo::file_upload_max_size());


        $this->addSubmit('send', 'Uložit');
//            ->setAttribute('data-dismiss', 'modal');

        $this->onSuccess[] = [$this, 'success'];


        $this->addFormClass(['ajax']);

        return $this;
    }


    public function success(DevrunForm $form, $values)
    {
        $entity = $form->getEntity();
        $ignore = ['id'];
        foreach ($values as $key => $value) {
            if (isset($entity->$key) && !in_array($key, $ignore)) {
                $entity->$key = $value;
            }
        }




    }


}