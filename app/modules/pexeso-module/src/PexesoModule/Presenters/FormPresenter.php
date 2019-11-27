<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 14.2.17
 * Time: 16:42
 */

namespace PexesoModule\Presenters;

use Devrun\CmsModule\Entities\PackageEntity;
use Devrun\ContestModule\Forms\RegistrationForm;
use Nette\Application\UI\Form;
use PexesoModule\Entities\ResultEntity;

/**
 * Class FormPresenter
 *
 * @package FrontModule\Presenters
 */
class FormPresenter extends BaseAppPresenter
{

    /** @var \Devrun\ContestModule\Forms\IRegistrationFormFactory @inject */
    public $registrationFormFactory;






    /**
     * @parent(pexeso:homepage:default)
     * @param null $id
     */
    public function actionDefault()
    {

    }


    /**
     * @param $name
     *
     * @return RegistrationForm
     */
    protected function createComponentRegistrationForm($name)
    {
        $form = $this->registrationFormFactory->create();
        $form->setTranslator($this->translator->domain('pexeso.' . $name));

//        dump($this->userEntity);


        $em = $this->userRepository->getEntityManager();

        /** @var PackageEntity $packageEntity */
        $packageEntity = $this->getPackageEntity();

        $form->callReloadEntity = function ($userEntity, $entity) use ($em, $packageEntity) {
//            dump($userEntity);
//            dump($entity);

            if (!$entity = $em->getRepository(ResultEntity::class)->findOneBy(['createdBy' => $userEntity, 'package' => $packageEntity ])) {
                $entity = new ResultEntity($userEntity, $packageEntity);
            }

//            $entity = $newEntity;

            return $entity;
        };

        $form
            ->create()
            ->addGender()
            ->addAddress()
            ->addPrivacy();
//        $form->addBirthDay();

        if ($this->locale == 'hu') {
            $form->addCheckbox('privacy2')
                 ->setAttribute("tabindex", $this->locale == 'hu' ? 25 : 25)
                 ->addRule(Form::FILLED, 'potvrďte_souhlas_s_pravidly_soutěže2')
                ->controlPrototype->class = 'id-privacy2';
        }

        $form->addNewsletter();
        $form->addHidden('quizOne', 'Sq1');

        if (!$resultEntity = $em->getRepository(ResultEntity::class)->findOneBy(['createdBy' => $this->userEntity, 'package' => $packageEntity ])) {
            $resultEntity = new ResultEntity($this->userEntity, $packageEntity);
        }


//        dump($resultEntity);

        $form->bindEntity($resultEntity);

        $day = $month = $year = null;
        if ($user = $resultEntity->getCreatedBy()) {
            if ($birthDay = $user->getBirthDay()) {
                $day = $birthDay->format('j');
                $month = $birthDay->format('n');
                $year = $birthDay->format('Y');
            }
        }

        $form->bootstrap3Render();
        $form->setDefaults([
            'originalEmail'   => $this->userEntity->getEmail(),
            'quizOne'   => 280,
            'day'   => $day,
            'month' => $month,
            'year'  => $year,
        ]);



        $form->onSuccess[] = function (RegistrationForm $form, $values) {
            $this->payload->ok = true;
            $this->sendPayload();
        };

        return $form;
    }


}