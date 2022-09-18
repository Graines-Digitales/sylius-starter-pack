<?php

namespace App\EventListener;

use App\Data\WebPageAction as WebPageDataAction;
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

        

        if($entity->getLocale() == 'en_GB') {
            $serializer = $this->container->get('serializer');
            $form = $this->container->get('form.factory')->create(WebPageTranslationType::class);
            $currentData = $serializer->normalize($entity, null);
            $referenceData = $serializer->normalize(
                $entity->getTranslatable()->getTranslation('fr_FR'), 
                null
            );

            $currentData = $this->syliusTranslator->translateEntity($currentData, $referenceData, $form);
            $this->webPageDataAction->hydrate($currentData, $entity->getTranslatable(), 'en_GB');
        }

        // dump($this->webContentWebPageService);die;
        $this->webContentWebPageService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
        $metaData = $this->metaDataService->getData($entity);
        $this->webContentSEOService->defineStructuredData($metaData, 'WebPage', $entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }
        
        $this->webContentWebPageService->moreData($entity);
        $this->webContentSEOService->defineMetaData($entity);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof WebPageTranslation) {
            return;
        }

        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentWebPageService->updateSlug($entity);
            $this->entityManager->flush($entity);
        }
    }

}