<?php

namespace App\EventListener;

use App\Entity\ArticleTranslation;
use App\WebContent\SEO;
use App\WebContent\Article as WebContentArticle;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ArticleListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $webContentArticleService;
    
    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , WebContentArticle $webContentArticleService
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->webContentArticleService = $webContentArticleService;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ArticleTranslation) {
            return;
        }

        $this->webContentArticleService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ArticleTranslation) {
            return;
        }

        $this->webContentArticleService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
    }
   
}