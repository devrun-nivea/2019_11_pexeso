<?php
/**
 * This file is part of souteze.pixman.cz.
 * Copyright (c) 2019
 *
 * @file    UserPermission.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\Security;

use Devrun\CmsModule\Repositories\PackageRepository;
use Devrun\Security\Authorizator;
use Nette\Security\IAuthorizator;
use Nette\Security\Permission;
use Nette\Security\User;

/**
 * Class UserPermission
 *
 * @package PexesoModule\Security
 */
class UserPermission implements \Devrun\Security\IAuthorizator
{

    /** @var User */
    private $user;

    /** @var Authorizator */
    private $authorizator;

    /** @var PackageRepository */
    private $packageRepository;

    /**
     * UserPermission constructor.
     *
     * @param User              $user
     * @param IAuthorizator     $authorizator
     * @param PackageRepository $packageRepository
     */
    public function __construct(User $user, IAuthorizator $authorizator, PackageRepository $packageRepository)
    {
        $this->user              = $user;
        $this->authorizator      = $authorizator;
        $this->packageRepository = $packageRepository;
        $this->addResources();
    }


    /**
     * set permissions allow
     *
     * @return void
     */
    public function setPermissions()
    {
        $result = $this->packageRepository->countBy(['module' => 'pexeso', 'user' => $this->user->getId()]);



        $this->authorizator->allow('member', 'Cms:Article:Default', ['default', 'edit'], function (IAuthorizator $autorizator, $role) use ($result) {
            return $result;
        });


        $this->authorizator->allow('member', 'Cms:Pexeso:Cards', Permission::ALL, function (IAuthorizator $autorizator, $role) use ($result) {
            return $result;
        });


        $this->authorizator->allow('member', 'Cms:Pexeso:Result', Permission::ALL, function (IAuthorizator $autorizator, $role) use ($result) {
            return $result;
        });


        $this->authorizator->allow('member', 'Cms:Pexeso:Theme', Permission::ALL, function (IAuthorizator $autorizator, $role) use ($result) {
            return $result;
        });
    }


    private function addResources()
    {
        $this->authorizator->addResource('Cms:Pexeso:Result');
        $this->authorizator->addResource('Cms:Pexeso:Cards');
        $this->authorizator->addResource('Cms:Pexeso:Settings');
        $this->authorizator->addResource('Cms:Pexeso:Theme');

    }


}