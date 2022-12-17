<?php

namespace App\EventListener;

use App\Data\Action\ArticleAction as ArticleDataAction;
use App\Entity\ArticleTranslation;
use App\Form\Type\ArticleTranslationType;
use App\Translation\SyliusTranslator;
use App\WebContent\SEO;
use App\WebContent\Article as WebContentArticle;
use App\WebContent\MetaData;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ArticleListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $webContentArticleService;

    protected $syliusTranslator;

    protected $articleDataAction;

    protected $metaDataService;
    
    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , WebContentArticle $webContentArticleService
        , SyliusTranslator $syliusTranslator
        , ArticleDataAction $articleDataAction
        , MetaData $metaDataService
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->webContentArticleService = $webContentArticleService;
        $this->syliusTranslator = $syliusTranslator;
        $this->articleDataAction = $articleDataAction;
        $this->metaDataService = $metaDataService;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ArticleTranslation) {
            return;
        }

        if ($entity->getLocale() !== $this->container->getParameter('locale')) {
            $translatedData = $this->translate($entity);
            if (!empty($translatedData)) {
                $this->articleDataAction->hydrate(
                    $translatedData,
                    $entity->getTranslatable(),
                    $entity->getLocale()
                );
            }
        }
        
        $entity = $this->enrich($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ArticleTranslation) {
            return;
        }
        $entity = $this->enrich($entity);
    }

    private function enrich($entity)
    {
        $this->webContentArticleService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
        $metaData = $this->metaDataService->getData($entity);
        $this->webContentSEOService->defineStructuredData($metaData, $entity);

        return $entity;
    }

    private function translate($entity)
    {
        $serializer = $this->container->get('serializer');
        $form = $this->container->get('form.factory')->create(ArticleTranslationType::class);
        $currentData = $serializer->normalize($entity, null);
        $referenceData = $serializer->normalize(
            $entity->getTranslatable()->getTranslation($this->container->getParameter('locale')), 
            null
        );

        if(!empty($referenceData['components'])) {
            $translatedComponents = $this->syliusTranslator->translateComponents($currentData, $referenceData, $entity->getLocale());
            // dump($translatedComponents);die;
            $entity->setComponents(json_encode($translatedComponents));
        }
        

        return $this->syliusTranslator->translateEntity($currentData, $referenceData, $form, $entity->getLocale());
    }
   
}