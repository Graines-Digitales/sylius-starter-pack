<?php

namespace App\EventListener;

use App\Tools\Media;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use App\Entity\MediaObjectVideo;
use App\Entity\MediaObjectDocument;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectImageListener
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
        if (!$entity instanceof MediaObjectImage) {
            return;
        }
       
        $this->toolsMediaService->defineEntityMediaFromFile($entity);

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
       
        $this->toolsMediaService->defineEntityMediaFromFile($entity);
    }

}