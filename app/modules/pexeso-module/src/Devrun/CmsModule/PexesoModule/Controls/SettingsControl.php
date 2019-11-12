<?php
/**
 * This file is part of devrun-souteze.
 * Copyright (c) 2018
 *
 * @file    SettingsControl.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Controls;

use Devrun\Application\UI\Control\Control;
use Devrun\CmsModule\Controls\DataGrid;
use Devrun\CmsModule\Controls\FlashMessageControl;
use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\CmsModule\Presenters\PagePresenter;
use Nette\Utils\Validators;
use PexesoModule\Repositories\PexesoSettingsRepository;

interface ISettingsControlFactory
{
    /** @return SettingsControl */
    function create();
}

class SettingsControl extends Control
{

    /*
     * nastavení redirectů
     * nastavení počtu karet
     * ...
     */

    /** @var PackageEntity */
    private $package;

    /** @var PexesoSettingsRepository @inject */
    public $pexesoSettingsRepository;


    protected function attached($presenter)
    {
        if ($presenter instanceof PagePresenter) {
            $this->package = $presenter->getRouteEntity()->getPackage();
        }

        parent::attached($presenter);
    }


    public function render()
    {
        $template = $this->createTemplate();
        $template->render();
    }



    protected function createComponentSettingsControl()
    {
        $grid = new DataGrid();
        $grid->setTranslator($this->translator);
        $grid->setRefreshUrl(false);
        $grid->setRememberState(false);
        $grid->setColumnReset(false);
        $grid->setShowSelectedRowsCount(false);
        $grid->setAutoSubmit(false);
        $grid->setPagination(false);

        $model = $this->pexesoSettingsRepository->createQueryBuilder('e');

        if ($this->package === null) {
            $model->andWhere('e.package IS NULL');

        } else {
            $model->andWhere('e.package = :package')->setParameter('package', $this->package);
        }

        /** @var PagePresenter $presenter */
        $presenter = $this->getPresenter();


        $grid->setDataSource($model);

        $grid->addColumnText('cards', 'Cards')
            ->setEditableInputType('text', ['class' => 'form-control'])
            ->setEditableCallback(function($id, $value) use ($grid, $presenter) {
                if (Validators::is($value, $validate = 'numericint:2..20')) {
                    if ($entity = $this->pexesoSettingsRepository->find($id)) {
                        $entity->cards = $value;
                        $this->pexesoSettingsRepository->getEntityManager()->persist($entity)->flush();

                        $message = $this->translator->translate('pexeso.admin.settings_success', null);
                        $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, $this->translator->translate('pexeso.admin.settings_title'), FlashMessageControl::TOAST_SUCCESS);
                        $presenter->redrawControl('flash');
                        $grid->reload();

                        return true;
                    }
                }
                $message = "input not valid [$value != $validate]";
                $presenter->flashMessage($message, FlashMessageControl::TOAST_TYPE, $this->translator->translate('pexeso.admin.settings_title'), FlashMessageControl::TOAST_SUCCESS);
                $presenter->redrawControl('flash');
                $grid->reload();

                return $grid->invalidResponse($message);
            });


        return $grid;
    }


}