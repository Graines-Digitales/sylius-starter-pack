<?php

namespace App\Data\Action;

use App\Entity\Trip;
use App\Entity\TripTranslation;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class TripAction
{
    private $slugger;

    private $entityManager;

    private $mediaObjectImageAction;

    private $aggregateOfferAction;
    
    private $componentAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , MediaObjectImageAction $mediaObjectImageAction
        , AggregateOfferAction $aggregateOfferAction
        , ComponentAction $componentAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
        $this->aggregateOfferAction = $aggregateOfferAction;
        $this->componentAction = $componentAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(Trip::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['_slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['headline']))->lower()->toString();
        $entity = $this->entityManager
            ->getRepository(Trip::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new Trip();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new TripTranslation();
            $entity->addTranslation($entityTranslation); 
        }
        $entity = $this->hydrate($data, $entity, $locale);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }
    
    public function hydrate($data, $entity, $locale) 
    {   
        $entity->getTranslation()->setLocale($locale);
        $entity->getTranslation()->setTranslatable($entity);

        if (isset($data['isLocked'])) {
            $entity->setIsLocked($data['isLocked']);
        }

        if (isset($data['metaTitle'])) {
            $entity->getTranslation($locale)->setMetaTitle($data['metaTitle']);
        }

        if (isset($data['metaDescription'])) {
            $entity->getTranslation($locale)->setMetaDescription($data['metaDescription']);
        }

        if (isset($data['headline'])) {
            $entity->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['primaryImage']);
            $entity->setPrimaryImage($image);
        }

        if(isset($data['secondaryImage']) && !empty($data['secondaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['secondaryImage']);
            $entity->setSecondaryImage($image);
        }

        if(isset($data['alternativeHeadline'])){
            $entity->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['text'])){
            $entity->getTranslation($locale)->setText($data['text']);
        }

        if (isset($data['pushForward'])) {
            $entity->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $entity->getTranslation($locale)->setTextResume($data['textResume']);
        }
        
        if(isset($data['departure_date'])) {
            $date = new \DateTime($data['departure_date']);
            // dump($date);die;
            $entity->setDepartureTime($date);
        }

        if(isset($data['arrival_date'])) {
            $date = new \DateTime($data['arrival_date']);
            $entity->setArrivalTime($date);
        }

        if (isset($data['offers'])) {
            foreach ($data['offers'] as $key => $offer) {
                $aggregateOffer = $this->aggregateOfferAction->create($offer);
                if(null === $aggregateOffer) {
                    throw new \Exception('Error form TripAction relation field offers');
                }
                $entity->addOffer($aggregateOffer);
            }
        }

        if (isset($data['components']) && !empty($data['components'])) {
            $components = $this->componentAction->createComponents($data['components']);
            $entity->getTranslation($locale)->setComponents(
                json_encode($components, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }
        
        if (isset($data['structuredData']) && !empty($data['structuredData'])) {
            $entity->getTranslation($locale)->setStructuredData($data['structuredData']);
        }
        
        return $entity;
    }

}
