<?php

namespace App\EventListener;

use App\Tools\Media;
use App\Entity\MediaObject;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectListener
{
    protected $container;

    protected $toolsMediaService;

    public function __construct(
        ContainerInterface $container
        , Media $toolsMediaService
    )
    {
        $this->container = $container;
        $this->toolsMediaService = $toolsMediaService;
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObject) {
            return;
        }

        // $entityManager = $args->getObjectManager();
        $this->toolsMediaService->defineEntityMediaFromFile($entity);
        // $entityManager->persist($entity);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObject) {
            return;
        }

        $configurationProject = $this->container->getParameter('configuration_project');
        $mimeTypes = $configurationProject['media_encoding_formats']['image'];
        if (in_array($entity->getEncodingFormat(), $mimeTypes)) {
            $this->toolsMediaService->generateFiltersForMediaObject($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObject) {
            return;
        }

        $configurationProject = $this->container->getParameter('configuration_project');
        $mimeTypes = $configurationProject['media_encoding_formats']['image'];
        if (in_array($entity->getEncodingFormat(), $mimeTypes)) {
            $this->toolsMediaService->generateFiltersForMediaObject($entity);
        }
    }
}