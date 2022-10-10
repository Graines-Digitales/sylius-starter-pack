<?php

namespace App\Data\Action;

use App\Entity\HotelService;
use App\Entity\HotelServiceTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class HotelServiceAction
{
    private $slugger;

    private $entityManager;

    private $categoryAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , CategoryAction $categoryAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->categoryAction = $categoryAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(HotelService::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['name'])->lower()->toString();
        $entity = $this->entityManager
            ->getRepository(HotelService::class)
            ->findOneBySlug($slug)
        ;
        if (null === $entity) {
            $entity = new HotelService();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new HotelServiceTranslation();
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

        if (isset($data['withPicto'])) {
            $entity->setWithPicto($data['withPicto']);
        }

        if (isset($data['slugPicto'])) {
            $entity->setSlugPicto($data['slugPicto']);
        }

        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form HotelServiceAction relation field category');
            }
            $entity->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form HotelServiceAction relation field tags');
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

        if (isset($data['moreInfo'])) {
            $entity->getTranslation($locale)->setMoreInfo($data['moreInfo']);
        }
  
        return $entity;
    }

   
}
