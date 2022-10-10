<?php

namespace App\Data\Action;

use App\Entity\AggregateOffer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class AggregateOfferAction
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

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(AggregateOffer::class)
                ->findOneBySlug($data)
            ;
        }

        // $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['slug'])->lower()->toString();
        // $entity = $this->entityManager->getRepository(AggregateOffer::class)
        //     ->findOneBySlug($slug)
        // ;
        $entity = null;
        if(null === $entity) {
            $entity = new AggregateOffer();
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
        if (isset($data['price'])) {
            $entity->setPrice($data['price']);
        }
        if (isset($data['highPrice'])) {
            $entity->setHighPrice($data['highPrice']);
        }
        if (isset($data['lowPrice'])) {
            $entity->setLowPrice($data['lowPrice']);
        }
        if (isset($data['addOn'])) {
            $entity->setAddOn($data['addOn']);
        }
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (isset($data['price'])) {
            $entity->setPrice($data['price']);
        }
        if (isset($data['priceCurrency'])) {
            $entity->setPriceCurrency($data['priceCurrency']);
        }
        if (isset($data['availabilityEnd'])) {
            $entity->setAvailabilityEnd(new \DateTime($data['availabilityEnd']));
        }
        if (isset($data['availabilityStart'])) {
            $entity->setAvailabilityStart(new \DateTime($data['availabilityStart']));
        }

        return $entity;

    }

   
}
