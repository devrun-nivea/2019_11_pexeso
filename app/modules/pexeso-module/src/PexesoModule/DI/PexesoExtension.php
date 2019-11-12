<?php
/**
 * This file is part of the devrun2016
 * Copyright (c) 2016
 *
 * @file    FrontExtension.php
 * @author  Pavel Paulík <pavel.paulik@support.etnetera.cz>
 */

namespace PexesoModule\DI;

use Devrun\Config\CompilerExtension;
use Devrun\Security\IAuthorizator;
use Flame\Modules\Providers\IPresenterMappingProvider;
use Kdyby\Events\DI\EventsExtension;
use PexesoModule\Entities\PexesoSettingsCardsEntity;
use PexesoModule\Entities\PexesoSettingsEntity;
use PexesoModule\Entities\QuestionEntity;
use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Doctrine\DI\OrmExtension;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\DI\ContainerBuilder;
use Nette\Environment;
use PexesoModule\Entities\ResultEntity;
use PexesoModule\Filters\CommonFilter;

class PexesoExtension extends CompilerExtension implements IPresenterMappingProvider,
//    IRouterProvider,
    IEntityProvider
{


    public function loadConfiguration()
    {
        parent::loadConfiguration();

        /** @var ContainerBuilder $builder */
        $builder = $this->getContainerBuilder();
        $config  = $this->getConfig($this->defaults);


        $builder->addDefinition($this->prefix('permission.userPermission'))
            ->setFactory('PexesoModule\Security\UserPermission')
            ->addTag(IAuthorizator::TAG_USER_PERMISSION);

        $builder->addDefinition($this->prefix('filters.common'))
            ->setType(CommonFilter::class);




//        $builder->addDefinition($this->prefix('repository.user'))
//            ->setFactory('FrontModule\Repositories\UserRepository')
//            ->addTag(OrmExtension::TAG_REPOSITORY_ENTITY, UserEntity::class);

//        $builder->addDefinition($this->prefix('repository.question'))
//            ->setFactory('PexesoModule\Repositories\QuestionRepository')
//            ->addTag(OrmExtension::TAG_REPOSITORY_ENTITY, QuestionEntity::class);


        $builder->addDefinition($this->prefix('form.registrationFormFactory'))
            ->setImplement('PexesoModule\Forms\IRegistrationFormFactory')
            ->setInject(true);

        $builder->addDefinition($this->prefix('form.cardFormFactory'))
            ->setImplement('Devrun\CmsModule\PexesoModule\Forms\ICardFormFactory')
            ->setInject(true);



        /*
         * repositories
         */
        $builder->addDefinition($this->prefix('repository.result'))
            ->setFactory('PexesoModule\Repositories\ResultRepository')
            ->addTag(OrmExtension::TAG_REPOSITORY_ENTITY, ResultEntity::class);

        $builder->addDefinition($this->prefix('repository.settingsCards'))
            ->setFactory('PexesoModule\Repositories\SettingsCardsRepository')
            ->addTag(OrmExtension::TAG_REPOSITORY_ENTITY, PexesoSettingsCardsEntity::class)
            ->setInject(true);

        $builder->addDefinition($this->prefix('repository.pexesoSettings'))
            ->setFactory('PexesoModule\Repositories\PexesoSettingsRepository')
            ->addTag(OrmExtension::TAG_REPOSITORY_ENTITY, PexesoSettingsEntity::class);


        /*
         * facades
         */
        $builder->addDefinition($this->prefix('facade.result'))
            ->setFactory('PexesoModule\Facades\QuestionFacade');




        // subscribers
        $builder->addDefinition($this->prefix('listener.settingsCards'))
            ->setFactory('Devrun\CmsModule\PexesoModule\Listeners\PackageListener')
            ->addTag(EventsExtension::TAG_SUBSCRIBER);

        $builder->addDefinition($this->prefix('listener.module'))
            ->setFactory('Devrun\CmsModule\PexesoModule\Listeners\ModuleListener')
            ->addTag(EventsExtension::TAG_SUBSCRIBER);



    }


    /**
     * Returns array of ClassNameMask => PresenterNameMask
     *
     * @example return array('*' => 'Booking\*Module\Presenters\*Presenter');
     * @return array
     */
    public function getPresenterMapping()
    {
        return array(
            'Pexeso' => 'PexesoModule\*Module\Presenters\*Presenter',
        );
    }

    /**
     * Returns array of ServiceDefinition,
     * that will be appended to setup of router service
     *
     * @example https://github.com/nette/sandbox/blob/master/app/router/RouterFactory.php - createRouter()
     * @return \Nette\Application\IRouter
     */
    public function getRoutesDefinition()
    {
        $lang = Environment::getConfig('lang');

        $routeList     = new RouteList();
        $routeList[]   = $frontRouter = new RouteList('Pexeso');


        $defaultLocale    = 'cs';
        $availableLocales = 'cs';

        if ($translation = \Nette\Environment::getService('translation.default')) {
            $defaultLocale    = $translation->getDefaultLocale();
            if ($serviceAvailableLocales = $translation->getAvailableLocales()) {
                $availableLocales = implode('|', array_unique(preg_replace("/^(\w{2})_(.*)$/m", "$1", $serviceAvailableLocales)));
            }
        }

        $frontRouter[] = new Route("[<module>-]pexeso/[<locale=$defaultLocale $availableLocales>/][<package=default>/]<presenter>/<action>[/<id>]", array(
            'presenter' => 'Homepage',
            'action'    => 'default'
        ));


//        $frontRouter[] = new Route("[<locale={$lang} sk|hu|cs>/]<presenter>/<action>[/<id>]", array(
//            'presenter' => array(
//                Route::VALUE        => 'Homepage',
//                Route::FILTER_TABLE => array(
//                    'testovaci' => 'Test',
////                    'presmerovano' => 'TestRedirect',
//                ),
//            ),
//            'action'    => array(
//                Route::VALUE        => 'default',
//                Route::FILTER_TABLE => array(
//                    'operace-ok' => 'operationSuccess',
//                ),
//            ),
//            'id'        => null,
//            'locale'    => [
//                Route::FILTER_TABLE => [
//                    'cz'  => 'cs',
//                    'sk'  => 'sk',
//                    'pl'  => 'pl',
//                    'com' => 'en'
//                ]]
//        ));
        return $routeList;

    }


    /**
     * Returns associative array of Namespace => mapping definition
     *
     * @return array
     */
    function getEntityMappings()
    {
        return array(
            'PexesoModule\Entities' => dirname(__DIR__) . '/Entities/',
        );
    }
}