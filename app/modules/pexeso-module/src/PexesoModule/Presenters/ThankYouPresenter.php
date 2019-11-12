<?php
/**
 * This file is part of nivea-2017-07-diagnostika.
 * Copyright (c) 2017
 *
 * @file    ThankYouPresenter.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Presenters;

class ThankYouPresenter extends BaseAppPresenter
{

    public function renderDefault() {
        $this->template->scroll = true;

    }


}