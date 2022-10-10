<?php

namespace App\EventListener;

use App\Tools\Media;
use App\Entity\MediaObjectDocument;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectDocumentListener
{
    protected $container;
    
    protected $entityManager;

    protected $toolsMediaService;

    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
        , EntityManagerInterface $entityManager
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->toolsMediaService = $toolsMediaService;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return; 
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return; 
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return;
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return;
        }
    }
}