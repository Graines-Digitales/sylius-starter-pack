<?php

namespace App\EventListener;

use App\Tools\Media;
use App\Entity\MediaObjectVideo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectVideoListener
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
        if (!$entity instanceof MediaObjectVideo) {
            return;
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectVideo) {
            return;
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectVideo) {
            return;
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof MediaObjectVideo) {
            return;
        }
    }
}