<?php

namespace App\EventListener;

use App\Entity\WebPageTranslation;
use App\Entity\WebPage;
use App\WebContent\SEO;
use App\WebContent\WebPage as WebContentWebPage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class WebPageListener
{
    private $container;
    
    private $webContentSEOService;

    private $webContentWebPageService;
    
    private $entityManager;

    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , WebContentWebPage $webContentWebPageService
        , EntityManagerInterface $entityManager
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->webContentWebPageService = $webContentWebPageService;
        $this->entityManager = $entityManager;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        // if ($entity instanceof WebPage) {
        //     dump($entity->getTranslation('fr_FR'));
        //     foreach($entity->getTranslations()->getIterator() as $value) {
        //         dump($value);
        //     }
        //     die;
        // }
        if (!$entity instanceof WebPageTranslation) {
            return;
        }
        $this->execute($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }
        $this->execute($entity);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }

        // if (is_callable([$entity, 'getSlug'])) {

            if(false === $entity->getTranslatable()->getIsLocked()) {
                $entity = $this->webContentWebPageService->updateSlug($entity);
                $this->entityManager->flush($entity);
            }
            
        // }
        
    }

    private function execute($entity) 
    {
        if (is_callable([$entity, 'getTextResume'])) {
            $this->webContentWebPageService->moreData($entity);
        }
        
        if (is_callable([$entity, 'getMetaTitle'])) {
            $this->webContentSEOService->defineMetaData($entity);
        }
    }
}