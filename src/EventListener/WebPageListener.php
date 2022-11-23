<?php

namespace App\EventListener;

use App\Data\Action\WebPageAction as WebPageDataAction;
use App\WebContent\SEO;
use App\Entity\WebPageTranslation;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\WebPageTranslationType;
use App\Translation\SyliusTranslator;
use App\WebContent\MetaData;
use App\WebContent\WebPage as WebContentWebPage;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class WebPageListener
{
    private $container;
    
    private $webContentSEOService;

    private $webContentWebPageService;
    
    private $entityManager;

    private $webPageDataAction;

    private $syliusTranslator;

    private $metaDataService;

    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , WebContentWebPage $webContentWebPageService
        , EntityManagerInterface $entityManager
        , WebPageDataAction $webPageDataAction
        , SyliusTranslator $syliusTranslator
        , MetaData $metaDataService
    ){
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->webContentWebPageService = $webContentWebPageService;
        $this->entityManager = $entityManager;
        $this->webPageDataAction = $webPageDataAction;
        $this->syliusTranslator = $syliusTranslator;
        $this->metaDataService = $metaDataService;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }

        if($entity->getLocale() !== $this->container->getParameter('locale')) {
            $translatedData = $this->translate($entity);
            if(!empty($translatedData)) {
                $this->webPageDataAction->hydrate(
                    $translatedData,
                    $entity->getTranslatable(),
                    $entity->getLocale()
                );
            }
        }
        
        
        $entity = $this->enrich($entity);
        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentWebPageService->updateSlug($entity);  
        }
        
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }
        
        $entity = $this->enrich($entity);
    }

    private function enrich($entity)
    {
        $this->webContentWebPageService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
        $metaData = $this->metaDataService->getData($entity);
        $this->webContentSEOService->defineStructuredData($metaData, $entity);

        return $entity;
    }

    private function translate($entity)
    {
        $serializer = $this->container->get('serializer');
        $form = $this->container->get('form.factory')->create(WebPageTranslationType::class);
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