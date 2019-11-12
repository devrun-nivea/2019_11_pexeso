<?php
/**
 * This file is part of devrun-souteze.
 * Copyright (c) 2018
 *
 * @file    QuestionsGridControl.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace Devrun\CmsModule\PexesoModule\Controls;

use Devrun\Application\UI\Control\Control;
use Devrun\CmsModule\Controls\DataGrid;
use Devrun\Doctrine\Entities\UserEntity;
use Kdyby\Translation\ITranslator;
use Nette\Security\User;
use PexesoModule\Entities\QuestionEntity;
use PexesoModule\Facades\QuestionFacade;


interface IQuestionsGridControlFactory
{
    /** @return QuestionsGridControl */
    function create();
}

class QuestionsGridControl extends Control
{

    /** @var QuestionFacade @inject */
    public $questionFacade;

    /** @var User @inject */
    public $user;



    public function render()
    {
        $template = $this->createTemplate();

        $template->render();
    }


    public function handleDelete($id)
    {
        /** @var QuestionEntity $entity */
        if (!$entity= $this->questionFacade->getRepository()->find($id)) {
            $this->flashMessage('Záznam nenalezen', 'danger');
            $this->ajaxRedirect();
        }

        $this->questionFacade->getEntityManager()->remove($entity)->flush();
        $this->flashMessage("Záznam {$entity->createdBy->getEmail()} smazán", 'success');

        if ($this->presenter->isAjax()) {
            $this->presenter->redrawControl('flash');
            $this['resultGrid']->reload();
        }

        $this->ajaxRedirect();
    }



    protected function createComponentResultGrid($name)
    {

        $grid = new DataGrid();
        $grid->setRefreshUrl(false)->setRememberState(false);

        $allowedAll = $this->user->isAllowed('Cms:Page', 'editAllPackages');

        $model = $this->questionFacade->getRepository()->createQueryBuilder('a')
            ->addSelect("p")
            ->leftJoin('a.createdBy', 'u')
            ->join('a.package', 'p')
            ->where('u.role != :role')->setParameter('role', UserEntity::ROLE_SUPERUSER);

        if (!$allowedAll) {
            $model->andWhere('p.user = :user')->setParameter('user', $this->user->getId());
        }



//        dump($model->getQuery()->getResult());
//        die();


        $grid->setDataSource($model);

        $grid->addColumnDateTime('inserted', 'Záznam vložen')
            ->setAlign('text-left')
            ->setSortable()
            ->setFitContent()
            ->setFilterDateRange();

        $pack = $grid->addColumnText('package', 'Balíček', 'package.name')
            ->setAlign('text-left')
            ->setSortable()
            ->setFilterText('package.name')
            ->setCondition(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $value) {
                $queryBuilder->andWhere('p.name LIKE :like')->setParameter('like', "%$value%");
            })
            ->setPlaceHolder("Default");


        $grid->addColumnText('firstname', 'Jméno', 'createdBy.firstName')
            ->setSortable()
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $sort) {
                $queryBuilder->addOrderBy('u.firstName', $sort['createdBy.firstName']);
            })
            ->setFilterText(['u.firstName']);

        $grid->addColumnText('lastname', 'Příjmení', 'createdBy.lastName')
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $sort) {
                $queryBuilder->addOrderBy('u.lastName', $sort['createdBy.lastName']);
            })
            ->setSortable()
            ->setFilterText(['u.lastName']);

        $grid->addColumnText('email', 'E-mail', 'createdBy.email')
            ->setSortableCallback(function (\Kdyby\Doctrine\QueryBuilder $queryBuilder, $sort) {
                $queryBuilder->addOrderBy('u.email', $sort['createdBy.email']);
            })
            ->setSortable()
            ->setFilterText(['u.email']);




        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setIcon('trash fa-2x')
            ->setClass('ajax btn btn-xs btn-danger')
            ->setConfirm(function ($item) {
                return "Opravdu chcete smazat záznam [id: {$item->id}; {$item->createdBy->email}]?";
            });

        $grid->setItemsDetail(__DIR__ . '/questions_grid_detail.latte');
        $grid->setTranslator($this->translator);

        return $grid;
    }


}