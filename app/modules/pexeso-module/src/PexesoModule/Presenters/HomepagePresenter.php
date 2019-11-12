<?php

namespace PexesoModule\Presenters;

use PexesoModule\Entities\PexesoSettingsCardsEntity;
use PexesoModule\Repositories\PexesoSettingsRepository;
use PexesoModule\Repositories\SettingsCardsRepository;

class HomepagePresenter extends BaseAppPresenter
{

    const MIN_CARD_CHR = 'a';
    const MAX_CARD_CHR = 'j';

    /** @var SettingsCardsRepository @inject */
    public $settingsCardsRepository;

    /** @var PexesoSettingsRepository @inject */
    public $settingsRepository;

//    protected $enableAjaxLayout = true;


    public function actionDefault()
    {
        $cardList = $this->getDynamicCards();
        shuffle($cardList);

        $shuffleCards = [];
        foreach ($cardList as $index => $item) {
            $shuffleCards[$index % 4][] = $item;
        }

        $this->template->cards = $this->getRandomCards();
        $this->template->gtmBenefitNames = $this->getGtmCardBenefitNames();
        $this->template->gtmBenefitDescriptions = $this->getGtmCardBenefitDescriptions();
        $this->template->shuffleCards = $shuffleCards;

//        $this->template->benefits = $this->getBenefitNames();
        $this->template->cardsOnPage = min(count($cardList), $this->getCardsOnPage() * 2);
        $this->template->gtmCardNames = $this->getGtmCardNames();
        $this->template->gtmCardIndexNames = $this->getGtmCardIndexNames();

//        $this->template->redirectTo = $this->routeRepository->isPageClassPublished('form', $this->package)
//            ? $this->link("Form:")
//            : $this->link("Homepage:");

        $this->template->redirectTo = $this->link("Form:");



//        dump($this->template->redirectTo);
//        dump($this->template->cardsOnPage);
//        dump($this->template->cards);
//        dump($this->template->shuffleCards);
//        dump($this->template->gtmBenefitNames);
//        dump($this->template->gtmBenefitDescriptions);
//        dump($this->template->gtmCardNames);
//        dump($this->template->gtmCardIndexNames);
//        die();







        /*
         *  $this->template->a = rand(0, 1);
         *  $this->template->b = rand(0, 1);
         *  ...
         */

        // load images for homepage sections
//        $this->template->images = $this->imageRepository->findAssoc(['namespace' => "Homepage_{$this->locale}"], 'systemName');
//        $this->template->benefits = $this->imageRepository->findAssoc(['namespace' => "benefits"], 'systemName');

    }


    private function getGtmCardIndexNames()
    {
        $result = [];

        foreach ($this->getRandomCards() as $card) {
            $result[] = $card->gtmName;
        }

        return $result;
    }


    private function getGtmCardNames()
    {
        $result = [];

        foreach ($this->getRandomCards() as $card) {
            $result[$card->getName()] = $card->gtmName;
        }

        return $result;
    }


    private function getGtmCardBenefitNames()
    {
        $result = [];

        foreach ($this->getRandomCards() as $card) {
            $result[$card->getName()] = $card->getHeader();
        }

        return $result;
    }


    private function getGtmCardBenefitDescriptions()
    {
        $result = [];

        foreach ($this->getRandomCards() as $card) {
            $result[$card->getName()] = $card->getDescription();
        }

        return $result;
    }


    private function getCardsOnPage()
    {
        static $cardsOnPage;

        if (null === $cardsOnPage) {
            $cardsOnPage = 6;
            if ($settingsEntity = $this->settingsRepository->findOneBy(['package' => $this->getPackage()])) {
                $cardsOnPage = $settingsEntity->cards;
            }
        }

        return $this->cmsGarbageMode ? PHP_INT_MAX : $cardsOnPage;
    }

    /**
     * @return PexesoSettingsCardsEntity[]
     */
    private function getRandomCards()
    {
        static $cards;
        if (null === $cards) {
            $cardsOnPage = $this->getCardsOnPage();

            /** @var PexesoSettingsCardsEntity[] $rawCards */
            if ($rawCards = $this->settingsCardsRepository->findRandomActiveCards($cardsOnPage, $this->getPackage())) {
                foreach ($rawCards as $card) {
                    $cards[$card->getName()] = $card;
                }

            } else {
                $cards = [];
            }
        }

        return $cards;
    }


    private function getDynamicCards()
    {
        $cardList = [];
        $cards = $this->getRandomCards();

        foreach ($cards as $card) {
            for ($i = 1; $i <= 2; $i++) {

                $name = $card->name;
                $cardList[] = [
                    'id'     => $name.$i,
                    'name'     => $name,
                    'imgFront' => "{$name}{$i}-front",
                    'imgBack'  => "{$name}{$i}-back",
//                    'imgFront' => "{$inverse}{$i}-front",
//                    'imgBack'  => "{$inverse}{$i}-back",
                ];
            }
        }

        return $cardList;
    }

}
