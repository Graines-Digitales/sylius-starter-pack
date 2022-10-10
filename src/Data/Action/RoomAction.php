<?php

namespace App\Data\Action;

use App\Entity\HotelRoom;
use App\Entity\MediaObjectImage;
use App\Entity\HotelRoomTranslation;
use Doctrine\ORM\EntityManagerInterface;
use App\Data\Action\MediaObjectImageAction;
use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;


class RoomAction
{
    private $slugger;

    private $entityManager;

    private $mediaObjectImageAction;

    private $categoryAction;

    private $aggregateOfferAction;

    private $amenityFeatureAction;

    private $componentAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , MediaObjectImageAction $mediaObjectImageAction
        , CategoryAction $categoryAction
        , AggregateOfferAction $aggregateOfferAction
        , AmenityFeatureAction $amenityFeatureAction
        , ComponentAction $componentAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
        $this->categoryAction = $categoryAction;
        $this->aggregateOfferAction = $aggregateOfferAction;
        $this->amenityFeatureAction = $amenityFeatureAction;
        $this->componentAction = $componentAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(HotelRoom::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['name']))->lower()->toString();
        $entity = $this->entityManager->getRepository(HotelRoom::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new HotelRoom();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new HotelRoomTranslation();
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

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['primaryImage']);
            $entity->setPrimaryImage($image);
        }

        if(isset($data['secondaryImage']) && !empty($data['secondaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['secondaryImage']);
            $entity->setSecondaryImage($image);
        }

        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form RoomAction relation field category');
            }
            $entity->setCategory($data['category']);
        }

        if (isset($data['maximumOccupants'])) {
            $entity->setMaximumOccupants($data['maximumOccupants']);
        }

        if (isset($data['minimumOccupants'])) {
            $entity->setMinimumOccupants($data['minimumOccupants']);
        }

        if (isset($data['labelBgTransparent'])) {
            $entity->setLabelBgTransparent($data['labelBgTransparent']);
        }

        if (isset($data['numberOfRooms'])) {
            $entity->setNumberOfRooms($data['numberOfRooms']);
        }

        if (isset($data['amenities'])) {
            foreach ($data['amenities'] as $key => $amenitie) {
                $amenitie = $this->amenityFeatureAction->create($amenitie);
                if(null === $amenitie) {
                    throw new \Exception('Error form RoomAction relation field amenities');
                }
                $entity->addAmenityFeature($amenitie);
            }
        }

        if (isset($data['loungeAmenities']) && !empty($data['loungeAmenities'])) {
            dump($data['loungeAmenities']);
            dump('loungeAmenities');
            die;
        }

        if (isset($data['offers'])) {
            foreach ($data['offers'] as $key => $offer) {
                $aggregateOffer = $this->aggregateOfferAction->create($offer);
                if(null === $aggregateOffer) {
                    throw new \Exception('Error form RoomAction relation field offers');
                }
                $entity->addOffer($aggregateOffer);
            }
        }

        if(isset($data['name'])){
            $entity->getTranslation($locale)->setName($data['name']);
        }

        if(isset($data['description'])){
            $entity->getTranslation($locale)->setDescription($data['description']);
        }


        if (isset($data['components']) && !empty($data['components'])) {
            $components = $this->componentAction->createComponents($data['components']);
            $entity->getTranslation($locale)->setComponents(
                json_encode($components, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }

        if (isset($data['gallery'])) {
            $component = $this->componentAction->createComponentFromGallery($data['gallery']);
            $entity->getTranslation($locale)->setComponents(
                json_encode([ $component ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }

        return $entity;
    }
}
