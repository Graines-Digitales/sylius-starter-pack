<?php

namespace App\EventListener;

use App\WebContent\SEO;
use App\Entity\CategoryTranslation;
use Doctrine\ORM\EntityManagerInterface;
use App\WebContent\Category as WebContentCategory;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CategoryListener
{
    private $webContentCategoryService;
    
    private $entityManager;

    public function __construct(
        WebContentCategory $webContentCategoryService
        , EntityManagerInterface $entityManager
    )
    {
        $this->webContentCategoryService = $webContentCategoryService;
        $this->entityManager = $entityManager;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof CategoryTranslation) {
            return;
        }
        
        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentCategoryService->updateSlug($entity);  
        }
    }
}