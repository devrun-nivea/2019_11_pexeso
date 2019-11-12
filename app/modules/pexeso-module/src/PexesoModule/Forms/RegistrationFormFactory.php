<?php

namespace PexesoModule\Forms;

use Devrun\Doctrine\Entities\UserEntity;
use Devrun\Doctrine\Repositories\UserRepository;
use Kdyby\Translation\ITranslator;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use PexesoModule\Entities\ResultEntity;
use PexesoModule\Repositories\ResultRepository;
use Tracy\Debugger;

interface IRegistrationFormFactory
{
    /** @return RegistrationFormFactory */
    function create();
}

/**
 * Class RegistrationFormFactory
 *
 * @package PexesoModule\Forms
 */
class RegistrationFormFactory extends BaseForm
{
    const MIN_YEARS = 16;

    const YEAR_VALIDATOR = 'PexesoModule\Forms\RegistrationFormFactory::yearChecked';

    private $minYears = 16;

    /** @var string */
    private $locale;

    /** @var User @inject */
    public $user;

    /** @var ITranslator $translator @inject */
    public $translator;

    /** @var UserRepository @inject */
    public $userRepository;

    /** @var ResultRepository @inject */
    public $resultRepository;


    public function create()
    {
        $this->locale = $this->translator->getLocale();

        if ($this->locale == 'hu') $this->minYears = 18;

        $this->addRadioList('gender', 'pohlaví', array(0 => 'žena', 1 => 'muž'))
            ->setValue(0)
            ->setDefaultValue(0)
            ->addRule(Form::FILLED, 'zvolte_pohlaví')
            ->setAttribute("tabindex", 2)
            ->controlPrototype->class = 'inline-item';

        $this->addText('firstName', 'jméno')
            ->addRule(Form::FILLED, 'vyplňte_vaše_křestní_jméno')
//            ->setAttribute('placeholder', 'jméno')
            ->setAttribute("tabindex", $this->locale == 'hu' ? 4 : 3)
            ->controlPrototype->class = 'id-firstname';

        $this->addText('lastName', 'příjmení')
            ->addRule(Form::FILLED, 'vyplňte_vaše_příjmení')
//            ->setAttribute('placeholder', 'příjmení')
            ->setAttribute("tabindex", $this->locale == 'hu' ? 3 : 4)
            ->controlPrototype->class = 'id-lastname';

        $this->addText('email', 'e-mail')
            ->addRule(Form::EMAIL, 'vyplňte_platný_e-mail')
            ->addRule(Form::FILLED, 'vyplňte_e-mail')
//            ->setAttribute('placeholder', 'e-mail')
            ->setAttribute("tabindex", 6)
            ->controlPrototype->class = 'no-margin';



        $days = array();
        foreach (range(1, 31) as $index) {
            $days[$index] = $index;
        }

        $this->addSelect('day', 'den', $days)
            ->setPrompt($this->translator->translate('pexeso.registrationForm.den'))
            ->setTranslator(null)
            ->setAttribute("tabindex", $this->locale == 'hu' ? 11 : 10)
            ->setAttribute('placeholder', 'den')
            ->addRule(Form::FILLED, 'vyplňte_den_narození_správně')
            ->addCondition(Form::FILLED)
            ->addRule(Form::RANGE, 'vyplňte_den_narození_správně', array(1, 31));
        $this['day']->controlPrototype->class = 'select-day';
        $this['day']->controlPrototype->style = 'width: 100%';

        $month = array();
        foreach (range(1, 12) as $index) {
            $month[$index] = $index;
        }
        $this->addSelect('month', 'měsíc', $month)
            ->setPrompt($this->translator->translate('pexeso.registrationForm.měsíc'))
            ->setTranslator(null)
            ->setAttribute("tabindex", $this->locale == 'hu' ? 10 : 11)
            ->addRule(Form::FILLED, 'vyplňte_měsíc_narození_správně')
            ->addCondition(Form::FILLED)
            ->addRule(Form::RANGE, 'vyplňte_měsíc_narození_správně', array(1, 12));
        $this['month']->controlPrototype->class = 'select-month';
        $this['month']->controlPrototype->style = 'width: 100%';

        $currentYear = intval(date('Y'));
        $years       = array();
        for ($index = $currentYear; $index >= 1900; $index--) {
            $years[$index] = $index;
        }


        $this->addSelect('year', 'rok', $years)
            ->setPrompt($this->translator->translate('pexeso.registrationForm.rok'))
            ->setTranslator(null)
            ->setAttribute("tabindex", $this->locale == 'hu' ? 9 : 12)
            ->addRule(Form::FILLED, 'musíte_být_starší_x_let')
            ->addCondition(Form::FILLED)
//            ->addRule(Form::RANGE, 'musíte_být_starší_x_let', array(null, $currentYear - $this->minYears))
            ->addRule(self::YEAR_VALIDATOR, 'musíte_být_starší_x_let', [$this['day'], $this['month'], intval($this->minYears)]);

        $this['year']->controlPrototype->class = 'select-year';
        $this['year']->controlPrototype->style = 'width: 100%';

        $this['day']->addConditionOn($this['month'], Form::FILLED)->addRule(Form::RANGE, 'vyplňte_den_narození_správně', array(1, 31));
        $this['day']->addConditionOn($this['year'], Form::FILLED)->addRule(Form::RANGE, 'vyplňte_den_narození_správně', array(1, 31));
        $this['month']->addConditionOn($this['day'], Form::FILLED)->addRule(Form::RANGE, 'vyplňte_měsíc_narození_správně', array(1, 12));
        $this['month']->addConditionOn($this['year'], Form::FILLED)->addRule(Form::RANGE, 'vyplňte_měsíc_narození_správně', array(1, 12));
        $this['year']->addConditionOn($this['day'], Form::FILLED)->addRule(Form::RANGE, 'musíte_být_starší_x_let', array(null, $currentYear - $this->minYears));
        $this['year']->addConditionOn($this['month'], Form::FILLED)->addRule(Form::RANGE, 'musíte_být_starší_x_let', array(null, $currentYear - $this->minYears));


        if ($this->locale == 'hu') {
            $this->addText('street', 'ulice')
                ->addRule(Form::FILLED, 'vyplňte_vaši_ulici')
//            ->setAttribute('placeholder', 'ulice')
                ->setAttribute("tabindex", 15)
                ->controlPrototype->class = 'no-margin input-short';

            /*
            $this->addText('strno', 'č.p.')
                ->addRule(Form::FILLED, 'vyplňte_číslo_popisné')
                // ->addRule(Form::PATTERN, 'vyplňte_číslo_popisné_správně', '[\/0-9]+[aA-zZ]*')
    //            ->setAttribute('placeholder', 'č.p.')
                ->setAttribute("tabindex", 6)
                ->controlPrototype->class = 'input-shorter';
            */

            $this->addText('city', 'město')
                ->addRule(Form::FILLED, 'vyplňte_město')
//            ->setAttribute('placeholder', 'město')
                ->setAttribute("tabindex", 18)
                ->controlPrototype->class = 'input-short';

            $this->addText('zip', 'PSČ')
                ->addRule(Form::FILLED, 'vyplňte_psč')
//            ->setAttribute('placeholder', 'PSČ')
                ->setAttribute("tabindex", 16)
                ->controlPrototype->class = 'no-margin input-shorter';

            if ($this->locale == 'hu') {
                $this['zip']
                    ->addRule(Form::INTEGER, 'vyplňte_psč_správně')
                    ->addRule(Form::LENGTH, 'vyplňte_psč_správně', 4);

            } elseif ($this->locale == 'sk') {
                $this['zip']
                    ->addRule(Form::LENGTH, 'vyplňte_psč_správně', 5)
                    ->addRule(Form::PATTERN, 'vyplňte_psč_správně', '([0-9]){5}');

            } else {
                $this['zip']
                    ->addRule(Form::LENGTH, 'vyplňte_psč_správně', 5)
                    ->addRule(Form::PATTERN, 'vyplňte_psč_správně', '([1-9]{1})([0-9]){4}');
            }

        }


        $this->addCheckbox('privacy')
            ->setAttribute("tabindex", 23)
            ->addRule(Form::FILLED, 'potvrďte_souhlas_s_pravidly_soutěže')
            ->controlPrototype->class = 'id-privacy';

        if ($this->locale == 'hu') {
            $this->addCheckbox('privacy2')
                ->setAttribute("tabindex", $this->locale == 'hu' ? 25 : 25)
                ->addRule(Form::FILLED, 'potvrďte_souhlas_s_pravidly_soutěže2')
                ->controlPrototype->class = 'id-privacy2';
        }

        $this->addCheckbox('newsletter')
            ->setAttribute("tabindex", $this->locale == 'hu' ? 24 : 24)
            ->controlPrototype->class = 'id-newsletter';


        $btn       = $this->addSubmit('send', 'odeslat')
            ->setAttribute('class', 'btn-md')
            ->setAttribute("tabindex", 26)
            ->getControlPrototype();
        $btn->setName("button")
            ->setText($this->translator->translate('pexeso.registrationForm.odeslat'))
            ->type = 'submit';
        $btn->create('strong class="space"');

        $this->onSuccess[] = array($this, 'success');

        $this->getElementPrototype()->class[] = 'nivea-form';
//        $this->getElementPrototype()->class[] = 'ajax';
    }


    /**
     * validate better birthday
     *
     * @param Nette\Forms\Controls\BaseControl $control
     * @param                                  $values
     *
     * @return bool
     */
    public static function yearChecked(Nette\Forms\Controls\BaseControl $control, $values)
    {
        $year     = $control->getValue();
        $day      = $values[0];
        $month    = $values[1];
        $minYears = $values[2];

        $date      = new Nette\Utils\DateTime("$year-$month-$day");
        $checkDate = new Nette\Utils\DateTime("-$minYears years");

        return $date < $checkDate;
    }


    public function success(Form $form, $values)
    {
        $presenter = $this->getPresenter();
        $section   = $presenter->getSession($this->section);
        $password = $values->firstName;

        /** @var $tempEntity UserEntity */
        $tempEntity = $this->getEntity();

        /** @var UserEntity $existUser */
        if ($existUser = $this->userRepository->findOneBy(['email' => $tempEntity->getEmail()])) {
            if ($existUser->getRole() == UserEntity::ROLE_MEMBER) {
                foreach ($values as $key => $value) {
                    if (isset($existUser->$key)) {
                        $existUser->$key = $tempEntity->$key;
                    }
                }
                $existUser->setFirstname($this->locale != 'hu' ? $tempEntity->getFirstname() : $tempEntity->getLastname());
                $existUser->setLastname($this->locale != 'hu' ? $tempEntity->getLastname() : $tempEntity->getFirstname());
            }

            $entity = $existUser;

        } else {
            $entity = $tempEntity;
            $entity
                ->setRole(UserEntity::ROLE_MEMBER);
        }

        if ($entity->getRole() == UserEntity::ROLE_MEMBER) {
            $entity
                ->setBirthdayFromParts($values->day, $values->month, $values->year)
                ->setUsername($values->email)
                ->setPassword($password);
        }

        $questionEntity = null;
        if ($entity->getId()) {
            $questionEntity = $this->resultRepository->findOneBy(['createdBy' => $entity]);
        }
        if (!$questionEntity) {
            $questionEntity = new ResultEntity($entity);
        }

        foreach ($section as $key => $val) {
            if (isset($questionEntity->$key)) {
                $questionEntity->$key = $val;
            }
        }

        try {
            $em = $this->getEntityMapper()->getEntityManager();
            $em->persist($entity)->persist($questionEntity);
            $em->flush();

            if (!$this->user->isLoggedIn()) {
                $this->user->login($entity->getUsername(), $password);
            }

            $section->registrationSend = true;
            $section->setExpiration('2 hours', 'registrationSend');

        } catch (\Kdyby\Doctrine\DuplicateEntryException $exc) {
            if (Nette\Utils\Strings::contains($exc->getMessage(), "1062")) {
                $message = 'email_již_existuje';
                $form->getPresenter()->flashMessage($presenter->translator->translate($message));
                return;
            }

            throw new \Kdyby\Doctrine\DuplicateEntryException($exc);
        }

    }

}
