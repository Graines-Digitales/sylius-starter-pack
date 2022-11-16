<?php

namespace App\EventListener;

use App\Entity\WebPageTranslation;
use App\Entity\ComponentTranslation;
use App\Translation\SyliusTranslator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\WebContent\Component as WebContentComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ComponentListener
{
    private $container;
    
    private $webContentComponentService;
    
    private $entityManager;

    private $syliusTranslator;

    public function __construct(
        ContainerInterface $container
        , WebContentComponent $webContentComponentService
        , EntityManagerInterface $entityManager
        , SyliusTranslator $syliusTranslator
    ){
        $this->container = $container;
        $this->webContentComponentService = $webContentComponentService;
        $this->entityManager = $entityManager;
        $this->syliusTranslator = $syliusTranslator;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ComponentTranslation) {
            return;
        }

        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentComponentService->updateSlug($entity);  
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ComponentTranslation) {
            return;
        }
        
        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentComponentService->updateSlug($entity);  
        }
    }

}