<?php

namespace App\Data\Action;

use App\Entity\HotelActivity;
use App\Entity\MediaObjectImage;
use App\Entity\HotelActivityTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class HotelActivityAction
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
                ->getRepository(HotelActivity::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(HotelActivity::class)
            ->findOneBySlug($slug)
        ;
        if (null === $entity) {
            $entity = new HotelActivity();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new HotelActivityTranslation();
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
                throw new \Exception('Error form HotelActivityAction relation  field category');
            }
            $entity->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form HotelActivityAction relation  field tags');
                }
                $entity->addTag($category);
            }
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
       
        if(isset($data['name'])){
            $entity->getTranslation($locale)->setName($data['name']);
        }

        if(isset($data['label'])){
            $entity->getTranslation($locale)->setLabel($data['label']);
        }

        if (isset($data['value'])) {
            $entity->getTranslation($locale)->setValue($data['value']);
        }
        

        return $entity;
    }

   
}
