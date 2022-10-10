<?php

namespace App\Data\Action;

use App\Entity\HotelTypicalDay;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\HotelTypicalDayTranslation;
use Symfony\Component\String\Slugger\SluggerInterface;


class HotelTypicalDayAction
{
    private $slugger;

    private $entityManager;

    private $categoryAction;

    private $hotelTypicalDayElementAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , CategoryAction $categoryAction
        , HotelTypicalDayElementAction $hotelTypicalDayElementAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->categoryAction = $categoryAction;
        $this->hotelTypicalDayElementAction = $hotelTypicalDayElementAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(HotelTypicalDay::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['name'])->lower()->toString();
        $entity = $this->entityManager
            ->getRepository(HotelTypicalDay::class)
            ->findOneBySlug($slug)
        ;
        if (null === $entity) {
            $entity = new HotelTypicalDay();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new HotelTypicalDayTranslation();
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
                throw new \Exception('Error form HotelTypicalDayAction relation field category');
            }
            $entity->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form HotelTypicalDayAction relation field tags');
                }
                $entity->addTag($category);
            }
        }

        if(isset($data['name'])){
            $entity->getTranslation($locale)->setName($data['name']);
        }

        if(isset($data['description'])){
            $entity->getTranslation($locale)->setDescription($data['description']);
        }

        if(isset($data['elements'])){
            foreach ($data['elements'] as $key => $element) {
                $element = $this->hotelTypicalDayElementAction->create($element);
                if(null === $element) {
                    throw new \Exception('Error form HotelTypicalDayAction relation field elements');
                }
                $entity->addElement($element);
            }
        }

        return $entity;
    }

   
}
