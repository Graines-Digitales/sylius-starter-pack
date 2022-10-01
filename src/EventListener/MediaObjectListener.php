<?php

namespace App\EventListener;

use App\Tools\Media;
use App\Entity\IconMediaObject;
use App\Entity\ImageMediaObject;
use App\Entity\VideoMediaObject;
use App\Entity\DocumentMediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectListener
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
        if ($entity instanceof ImageMediaObject
            || $entity instanceof VideoMediaObject
            || $entity instanceof IconMediaObject
            || $entity instanceof DocumentMediaObject
        ) {
            
            $this->toolsMediaService->defineEntityMediaFromFile($entity);
        }
       
        return;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ImageMediaObject) {
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
        if (!$entity instanceof ImageMediaObject) {
            return;
        }

        $configurationProject = $this->container->getParameter('configuration_project');
        $mimeTypes = $configurationProject['media_encoding_formats']['image'];
        
        $entity = $this->toolsMediaService->defineEntityMediaFromFile($entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        if (in_array($entity->getEncodingFormat(), $mimeTypes)) {
            $this->toolsMediaService->generateFiltersForMediaObject($entity);
        }
    }
}