<?php

namespace App\EventListener;

use App\Data\Action\MediaObjectImageAction;
use App\Tools\Media;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectImageListener
{
    protected $container;
    
    protected $entityManager;

    protected $toolsMediaService;

    protected $mediaObjectImageAction;

    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
        , EntityManagerInterface $entityManager
        , MediaObjectImageAction $mediaObjectImageAction
    )
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->toolsMediaService = $toolsMediaService;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectImage) {
            return;
        }
       
        $this->mediaObjectImageAction->hydrate([], $entity, 'fr');
        $configurationProject = $this->container->getParameter('configuration_project');
        $mimeTypes = $configurationProject['media_encoding_formats']['image'];
        if (in_array($entity->getEncodingFormat(), $mimeTypes)) {
            $this->toolsMediaService->generateFiltersForMediaObject($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectImage) {
            return;
        }
        $this->mediaObjectImageAction->hydrate([], $entity, 'fr');
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectImage) {
            return;
        }

        $this->toolsMediaService->remove($entity);
    }

}