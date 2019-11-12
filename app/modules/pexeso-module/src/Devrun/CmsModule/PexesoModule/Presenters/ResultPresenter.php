<?php
/**
 * This file is part of pexeso-devrun.
 * Copyright (c) 2018
 *
 * @file    ResultPresenter.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Presenters;

use Devrun\CmsModule\PexesoModule\Controls\IQuestionsGridControlFactory;
use Devrun\CmsModule\Presenters\AdminPresenter;
use Devrun\Doctrine\Repositories\UserRepository;
use PexesoModule\Repositories\QuestionRepository;
use PexesoModule\Repositories\ResultRepository;

class ResultPresenter extends AdminPresenter
{

    /** @var UserRepository @inject */
    public $userRepository;

    /** @var ResultRepository @inject */
    public $questionRepository;

    /** @var IQuestionsGridControlFactory @inject */
    public $questionsGridControlFactory;



    public function handleRemove($id)
    {

    }




    protected function createComponentQuestionsGridControl($name)
    {
        $control = $this->questionsGridControlFactory->create();

        return $control;
    }


}