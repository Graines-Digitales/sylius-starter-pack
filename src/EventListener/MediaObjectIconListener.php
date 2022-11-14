<?php

namespace App\EventListener;

use App\Data\Action\MediaObjectIconAction;
use App\Tools\Media;
use App\Entity\MediaObjectIcon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectIconListener
{
    protected $container;
    
    protected $entityManager;

    protected $toolsMediaService;

    protected $mediaObjectIconAction;

    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
        , EntityManagerInterface $entityManager
        , MediaObjectIconAction $mediaObjectIconAction
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->toolsMediaService = $toolsMediaService;
        $this->mediaObjectIconAction = $mediaObjectIconAction;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectIcon) {
            return;
        }

        $this->mediaObjectIconAction->hydrate([], $entity, 'fr');
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectIcon) {
            return;
        }

        $this->mediaObjectIconAction->hydrate([], $entity, 'fr');
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectIcon) {
            return;
        }

        $this->toolsMediaService->removeIcon($entity);
    }
}