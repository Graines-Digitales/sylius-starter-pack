<?php

namespace App\Data\Action;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class CategoryAction
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
                ->getRepository(Category::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['name']))->lower()->toString();
        $entity = $this->entityManager->getRepository(Category::class)
            ->findOneBySlug($slug);
        
        if(null === $entity ) {
            $entity = new Category();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new CategoryTranslation();
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
        // $entity->getTranslation()->setTranslatable($entity);

        if(isset($data['name'])) {
            $entity->getTranslation($locale)->setName($data['name']);
        }

        if(isset($data['type']) && true == $data['type']) {
            $category = $this->create($data['type']);
            $entity->setParent($category);
        } else {
            
            $category = $this->entityManager
                ->getRepository(Category::class)
                ->findOneBySlug('root')
            ;
            if(null !== $category) {
                $entity->setParent($category);
            }
        }
      
        return $entity;
    }

}
