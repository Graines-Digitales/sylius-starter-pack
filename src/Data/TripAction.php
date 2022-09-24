<?php

namespace App\Data;

use App\Entity\Trip;
use App\Entity\TripTranslation;
use App\Entity\ImageMediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class TripAction
{
    private $slugger;

    private $entityManager;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function create($data = [], $locale = 'fr_FR')
    {
        $trip = new Trip();
        $trip->setCurrentLocale($locale);
        $tripTranslation = new TripTranslation();
        $trip->addTranslation($tripTranslation); 
        $trip = $this->hydrate($data, $trip, $locale);

        return $trip;
    }
    
    public function hydrate($data, $trip, $locale) 
    {   
        $trip->getTranslation()->setLocale($locale);
        $trip->getTranslation()->setTranslatable($trip);

        if (isset($data['isLocked'])) {
            $trip->setIsLocked($data['isLocked']);
        }

        if (isset($data['metaTitle'])) {
            $trip->getTranslation($locale)->setMetaTitle($data['metaTitle']);
        }

        if (isset($data['metaDescription'])) {
            $trip->getTranslation($locale)->setMetaDescription($data['metaDescription']);
        }

        if (isset($data['headline'])) {
            $trip->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            if(!$data['primaryImage'] instanceof ImageMediaObject) {
                $primaryImage = $this->entityManager->getRepository(ImageMediaObject::class)
                ->findOneBy(['filename' => $data['primaryImage'] ]);
                if(null !== $primaryImage) {
                    $trip->setPrimaryImage($primaryImage);
                }
            } else {
                $trip->setPrimaryImage($data['primaryImage']);
            }
        }

        if(isset($data['alternativeHeadline'])){
            $trip->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['text'])){
            $trip->getTranslation($locale)->setText($data['text']);
        }

        if (isset($data['pushForward'])) {
            $trip->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $trip->getTranslation($locale)->setTextResume($data['textResume']);
        }
        
        if(isset($data['departure_date'])) {
            $date = new \DateTime($data['departure_date']);
            // dump($date);die;
            $trip->setDepartureTime($date);
        }

        if(isset($data['arrival_date'])) {
            $date = new \DateTime($data['arrival_date']);
            $trip->setArrivalTime($date);
        }

        if (isset($data['offers'])) {
            foreach ($data['offers'] as $key => $offer) {

                $aggregateOffer = new \App\Entity\AggregateOffer();
                if(isset($offer['name'])) {
                    $aggregateOffer->setName($offer['name']);
                }
                
                if(isset($offer['price'])) {
                    $aggregateOffer->setPrice($offer['price']);
                }

                if(isset($offer['addOn'])) {
                    $aggregateOffer->setAddOn($offer['addOn']);
                }

                if(isset($offer['start'])) {
                    $start = new \DateTime($offer['start']);
                    $aggregateOffer->setAvailabilityStart($start);
                }

                if(isset($offer['end'])) {
                    $end = new \DateTime($offer['end']);
                    $aggregateOffer->setAvailabilityEnd($end);
                }
              
                $trip->addOffer($aggregateOffer);
            }
        }

        // $components = '{}';
        // if (isset($data['components'])) {
        //     $components = $data['component'];
        // }
        // $trip->setComponents($components, 'fr_FR');

        return $trip;
    }

}
