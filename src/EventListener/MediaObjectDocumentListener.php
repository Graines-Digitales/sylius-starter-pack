<?php

namespace App\EventListener;

use App\Data\Action\MediaObjectDocumentAction;
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

    protected $mediaObjectDocumentAction;

    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
        , EntityManagerInterface $entityManager
        , MediaObjectDocumentAction $mediaObjectDocumentAction
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->toolsMediaService = $toolsMediaService;
        $this->mediaObjectDocumentAction = $mediaObjectDocumentAction;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return; 
        }
        $this->mediaObjectDocumentAction->hydrate([], $entity, 'fr');
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectDocument) {
            return; 
        }
        $this->mediaObjectDocumentAction->hydrate([], $entity, 'fr');
    }

}