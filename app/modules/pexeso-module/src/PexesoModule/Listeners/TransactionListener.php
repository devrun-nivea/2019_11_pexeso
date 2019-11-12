<?php
/**
 * This file is part of the 2015_05_protect_and_bronze
 * Copyright (c) 2015
 *
 * @file    TransactionListener.php
 * @author  Pavel Paulík <pavel.paulik1@gmail.com>
 */

namespace PexesoModule\Listeners;


use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use PexesoModule\Entities\QuestionEntity;
use PexesoModule\Entities\UserEntity;
use Kdyby\Events\Subscriber;
use Nette\Object;
use Tracy\Debugger;

class TransactionListener extends Object implements Subscriber
{

    const KEY_EMAIL     = 'Email';
    const KEY_BIRTHDAY  = 'Birthday';
    const KEY_FIRSTNAME = 'Firstname';
    const KEY_LASTNAME  = 'Lastname';
    const KEY_STREET    = 'Street1';
    const KEY_STREETNR  = 'Streetnumber';
    const KEY_ZIPCODE   = 'ZipCode';
    const KEY_CITY      = 'City';
    const KEY_GENDER    = 'Gender';

    const KEY_Q1 = 'Q1';
    const KEY_Q2 = 'Q2';
    const KEY_Q3 = 'Q3';
    const KEY_Q4 = 'Q4';
    const KEY_CREATED = 'Date_Created';

    const KEY_NAME         = 'name';
    const KEY_DATA         = 'Data';
    const KEY_ACTION       = 'action';
    const KEY_ATTRIBUTE    = 'Attribute';
    const KEY_ATTRIBUTES   = 'Attributes';
    const KEY_TRANSACTION  = 'Transaction';
    const KEY_LOGGER       = 'ProCampaign';

    const VALUE_NEWSLETTER = 'list:NIVEA_Newsletter';

    private $userAttributes = array();
    private $transAttributes = array();
    private $newsletter;

    private static $valUseProducts = array('No', 'Yes');

    /** @var array DI configuration */
    private $contest;


    public function __construct($contest)
    {
        $this->contest = $contest;
    }


    public function updateTransactionOne(QuestionEntity $question)
    {
        if ($this->contest['transaction']['send']) {
            $user = $question->getUser();
            if ($user->getEmail()) {
                $this->userAttributes[self::KEY_EMAIL] = $user->getEmail();
            }
            if ($question->getQuizOne()) {
                $this->transAttributes[self::KEY_Q1] = $question->getQuizOne();
            }

            $this->createRequest();
        }
    }

    public function updateTransactionTwo(QuestionEntity $question)
    {
        if ($this->contest['transaction']['send']) {
            $user = $question->getUser();
            if ($user->getEmail()) {
                $this->userAttributes[self::KEY_EMAIL] = $user->getEmail();
            }
            if ($question->getQuizOne()) {
                $this->transAttributes[self::KEY_Q2] = $question->getQuizTwo();
            }

            $this->createRequest();
        }
    }

    public function updateTransactionThree(QuestionEntity $question)
    {
        if ($this->contest['transaction']['send']) {
            $user = $question->getUser();
            if ($user->getEmail()) {
                $this->userAttributes[self::KEY_EMAIL] = $user->getEmail();
            }
            if ($question->getQuizOne()) {
                $this->transAttributes[self::KEY_Q3] = $question->getQuizThree();
            }

            $this->createRequest();
        }
    }

    public function updateTransactionFour(QuestionEntity $question)
    {
        if ($this->contest['transaction']['send']) {
            $user = $question->getUser();
            if ($user->getEmail()) {
                $this->userAttributes[self::KEY_EMAIL] = $user->getEmail();
            }
            if ($question->getQuizOne()) {
                $this->transAttributes[self::KEY_Q4] = $question->getQuizFour();
            }

            $this->createRequest();
        }
    }


    public function onUpdate($entity)
    {
        if (!isset($this->contest['transaction'])) {
            throw new \InvalidArgumentException('no defined transaction configutation');
        }

        if ($this->contest['transaction']['send']) {
            if ($entity instanceof UserEntity) {

                $this->updateTransAttributes($entity->getQuestions());
                $this->updateUserAttributes($entity);
                $this->updateTransactionDate();

//            } elseif ($entity instanceof QuestionEntity) {
//                $this->updateTransAttributes($entity);
//                $this->updateUserAttributes($entity->getUser());
            }

            $this->createRequest();
        }
    }


    private function updateUserAttributes(UserEntity $user)
    {
        if ($user->getEmail()) {
            $this->userAttributes[self::KEY_EMAIL] = $user->getEmail();
        }
        if ($user->getBirthday()) {
            $this->userAttributes[self::KEY_BIRTHDAY] = $user->getBirthday()->format('Y-m-d');
        }
        if ($user->getFirstname()) {
            $this->userAttributes[self::KEY_FIRSTNAME] = $user->getFirstname();
        }
        if ($user->getLastname()) {
            $this->userAttributes[self::KEY_LASTNAME] = $user->getLastname();
        }
        if ($user->getCity()) {
            $this->userAttributes[self::KEY_CITY] = $user->getCity();
        }
        if ($user->getGender() !== NULL) {
            $this->userAttributes[self::KEY_GENDER] = $user->getGender();
        }
        if ($user->getStreet()) {
            $this->userAttributes[self::KEY_STREET] = $user->getStreet();
        }
        if ($user->getStrno()) {
            $this->userAttributes[self::KEY_STREETNR] = $user->getStrno();
        }
        if ($user->getZip()) {
            $this->userAttributes[self::KEY_ZIPCODE] = $user->getZip();
        }
        if ($user->getNewsletter()) {
            $this->newsletter = $user->getNewsletter();
        }
    }


    public function updateTransactionDate()
    {
        $this->transAttributes[self::KEY_CREATED] = date('Y-m-d H:i:s');
    }


    private function updateTransAttributes(QuestionEntity $question)
    {
        if (NULL !== $question->getQuizOne()) {
            $this->transAttributes[self::KEY_Q1] = $question->getQuizOne();
        }
        if (NULL !== $question->getQuizTwo()) {
            $this->transAttributes[self::KEY_Q2] = $question->getQuizTwo();
        }
        if (NULL !== $question->getQuizThree()) {
            $this->transAttributes[self::KEY_Q3] = $question->getQuizThree();
        }
        if (NULL !== $question->getQuizFour()) {
            $this->transAttributes[self::KEY_Q4] = $question->getQuizFour();
        }
    }


    private function MultiByteConvertion( $str ) {
            $resultStr = mb_convert_encoding( '', 'UTF-8' );

            for( $i = 0; $i < mb_strlen( $str, 'UTF-8' ); ++$i ) {
                $byte = null;
                $byte = unpack( "v*", iconv( 'UTF-8', 'UTF-16LE', mb_substr( $str, $i, 1, 'UTF-8' ) ) );

                if( count( $byte ) > 0 )
                    $resultStr .= chr( $byte[1] );
            }

            return $resultStr;
	}

    private function createRequest()
    {
        $url    = str_replace('.cs', '.cz', $this->contest['transaction']['womenUrl']);
        $name   = $this->contest['transaction']['name'];
        $system = $this->contest['transaction']['system'];

        $doc = new \DOMDocument("1.0", "UTF-8");
        $doc->formatOutput = true;

        $data = $doc->createElement($system);
        $doc->appendChild($data);

        $data->setAttribute(self::KEY_ACTION, 'put');

        $compress = '';
        if (!empty($this->userAttributes)) {
            $attributes = $doc->createElement(self::KEY_ATTRIBUTES);
            $data->appendChild($attributes);
            foreach ($this->userAttributes as $key => $attribute) {

                if ($key == "Gender") {
                    $url = $attribute == 0
                        ? str_replace('.cs', '.cz', $this->contest['transaction']['womenUrl'])
                        : str_replace('.cs', '.cz', $this->contest['transaction']['menUrl']);
                }

                $attribut = $doc->createElement(self::KEY_ATTRIBUTE, $attribute);
                $attribut->setAttribute('name', $key);
                $attributes->appendChild($attribut);
            }
            if ($this->newsletter !== NULL) {
                $attribut = $doc->createElement(self::KEY_ATTRIBUTE, $this->newsletter);
                $attribut->setAttribute('name', self::VALUE_NEWSLETTER);
                $attributes->appendChild($attribut);
            }

            $compress .= $doc->saveXML($attributes);
        }


        if (!empty($this->transAttributes)) {
            $transAttributes = $doc->createElement(self::KEY_TRANSACTION);
            $transAttributes->setAttribute('name', $name);
            $data->appendChild($transAttributes);
            $transData = $doc->createElement(self::KEY_DATA);

            foreach ($this->transAttributes as $key => $attribute) {
                $attribut = $doc->createElement(self::KEY_ATTRIBUTE, $attribute);
                $attribut->setAttribute('name', $key);
                $transData->appendChild($attribut);
            }
            $transAttributes->appendChild($transData);
            $compress .= $doc->saveXML($transAttributes);
        }

        $match = preg_replace('/\s*/', '', $compress);
        $data->setAttribute('checksum', md5($this->MultiByteConvertion( $match )));
        $result   = $doc->saveXML();


//        dump($url);
//        dump($result);
//        die();

        $response = $this->sendRequest($result, $url, strlen($compress));

//        dump($response);
//        die();
//        return;

        if (!Debugger::$productionMode) {
            Debugger::$maxLen = 1000;
            $sender      = isset($this->userAttributes[self::KEY_EMAIL]) ? $this->userAttributes[self::KEY_EMAIL] : 'unknown';
            $extResponse = isset($response['@attributes']) ? $response['@attributes'] : $response;
            \Tracy\Debugger::log($sender . ' -request- ' . $result, self::KEY_LOGGER);
            $out = is_array($extResponse) ? implode(',', $extResponse) : $extResponse;
            \Tracy\Debugger::log($sender . ' -response- ' . $out, self::KEY_LOGGER);
            Debugger::barDump($result, 'result');
            Debugger::barDump($extResponse, 'response');
        }
    }


    /**
     * @param $request
     * @param $url
     *
     * @return bool|mixed
     */
    private function sendRequest($request, $url)
    {
        $headers = array(
            "Content-type: text/xml;charset=\"windows-1250\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"run\"",
            "Connection: close",
            "Content-Length: " . strlen($request)
        );

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);

            // send xml request to a server
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $data = curl_exec($ch);

            if ($data === false) {
                $error = curl_error($ch);
                \Tracy\Debugger::log($ch, self::KEY_LOGGER);

            } else {
                $data = json_decode(json_encode(simplexml_load_string($data)), true);
                return $data;
            }
            curl_close($ch);

        } catch (\Exception  $e) {
            \Tracy\Debugger::log($e, self::KEY_LOGGER);
        }
        return false;

    }


    /**
     * @param PostFlushEventArgs $eventArgs
     *
     * @throws \InvalidArgumentException
     */
    public function postFlush(PostFlushEventArgs  $eventArgs)
    {
        if (!isset($this->contest['transaction'])) {
            throw new \InvalidArgumentException('no defined transaction in configuration');
        }

        if ($this->contest['transaction']['send']) {
            foreach ($eventArgs->getEntityManager()->getUnitOfWork()->getIdentityMap() as $maps) {
                foreach ($maps as $entity) {
                    if ($entity instanceof UserEntity) {
                        $this->updateTransAttributes($entity->getQuestions());
                        $this->updateUserAttributes($entity);
                        $this->updateTransactionDate();

                    } elseif ($entity instanceof QuestionEntity) {
                        $this->updateTransAttributes($entity);
                        $this->updateUserAttributes($entity->getUser());
                    }
                }
            }

            $this->createRequest();
        }
    }


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
//            Events::postFlush,
        );
    }
}