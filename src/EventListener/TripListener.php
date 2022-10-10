<?php

namespace App\EventListener;

use App\Data\Action\TripAction as TripDataAction;
use App\Entity\TripTranslation;
use App\Form\Type\TripTranslationType;
use App\Translation\SyliusTranslator;
use App\WebContent\MetaData;
use App\WebContent\SEO;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TripListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $syliusTranslator;

    protected $tripDataAction;

    protected $metaDataService;

    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , SyliusTranslator $syliusTranslator
        , TripDataAction $tripDataAction
        , MetaData $metaDataService
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->syliusTranslator = $syliusTranslator;
        $this->tripDataAction = $tripDataAction;
        $this->metaDataService = $metaDataService;

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        $translatedData = $this->translate($entity);
        if(!empty($translatedData)) {
            $this->webPageDataAction->hydrate(
                $translatedData,
                $entity->getTranslatable(),
                $entity->getLocale()
            );
        }

        $this->webContentSEOService->defineMetaData($entity);
        $metaData = $this->metaDataService->getData($entity);
        $this->webContentSEOService->defineStructuredData($metaData, $entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        $this->webContentSEOService->defineMetaData($entity);
    }
   
    public function translate($entity)
    {
        $serializer = $this->container->get('serializer');
        $form = $this->container->get('form.factory')->create(TripTranslationType::class);
        $currentData = $serializer->normalize($entity, null);
        $referenceData = $serializer->normalize(
            $entity->getTranslatable()->getTranslation($this->container->getParameter('locale')), 
            null
        );

        return $this->syliusTranslator->translateEntity($currentData, $referenceData, $form, $entity->getLocale());
    }
}