<?php

namespace App\Data\Action;

use App\Entity\MediaObjectImage;
use App\Entity\HotelTypicalDayElement;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\HotelTypicalDayElementTranslation;
use Symfony\Component\String\Slugger\SluggerInterface;


class HotelTypicalDayElementAction
{
    private $slugger;

    private $entityManager;

    private $categoryAction;

    private $mediaObjectImageAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , CategoryAction $categoryAction
        , MediaObjectImageAction $mediaObjectImageAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->categoryAction = $categoryAction;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(HotelTypicalDayElement::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['name']))->lower()->toString();
        $entity = $this->entityManager->getRepository(HotelTypicalDayElement::class)
            ->findOneBySlug($slug)
        ;
        if (null === $entity) {
            $entity = new HotelTypicalDayElement();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new HotelTypicalDayElementTranslation();
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

        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form HotelTypicalDayElementAction relation field category');
            }
            $entity->setCategory($data['category']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['primaryImage']);
            $entity->setPrimaryImage($image);
        }

        if(isset($data['secondaryImage']) && !empty($data['secondaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['secondaryImage']);
            $entity->setSecondaryImage($image);
        }

        if (isset($data['label'])) {
            $entity->getTranslation()->setLabel($data['label']);
        }
        if (isset($data['value'])) {
            $entity->getTranslation()->setValue($data['value']);
        }

        if (isset($data['labelBgTransparent'])) {
            $entity->setLabelBgTransparent($data['labelBgTransparent']);
        }

        if (isset($data['hours'])) {
            $entity->setHours($data['hours']);
        }

        if (isset($data['description'])) {
            $entity->getTranslation()->setDescription($data['description']);
        }

        if (isset($data['event']) && !empty($data['event'])) {
            dump('event HotelTypicalDayElement');die;
        }
      
        return $entity;
    }

   
}
