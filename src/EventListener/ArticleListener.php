<?php

namespace App\EventListener;

use App\Data\ArticleAction as ArticleDataAction;
use App\Entity\ArticleTranslation;
use App\Form\Type\ArticleTranslationType;
use App\Translation\SyliusTranslator;
use App\WebContent\SEO;
use App\WebContent\Article as WebContentArticle;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ArticleListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $webContentArticleService;

    protected $syliusTranslator;

    protected $articleDataAction;
    
    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , WebContentArticle $webContentArticleService
        , SyliusTranslator $syliusTranslator
        , ArticleDataAction $articleDataAction
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->webContentArticleService = $webContentArticleService;
        $this->syliusTranslator = $syliusTranslator;
        $this->articleDataAction = $articleDataAction;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ArticleTranslation) {
            return;
        }

      
        
        if($entity->getLocale() == 'en_GB') {
            $serializer = $this->container->get('serializer');
            $form = $this->container->get('form.factory')->create(ArticleTranslationType::class);
            $currentData = $serializer->normalize($entity, null);
            $referenceData = $serializer->normalize(
                $entity->getTranslatable()->getTranslation('fr_FR'), 
                null
            );

            $currentData = $this->syliusTranslator->translateEntity($currentData, $referenceData, $form);
            $this->articleDataAction->hydrate($currentData, $entity->getTranslatable(), 'en_GB');
            // dump($currentData);
            // die;
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