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
use Symfony\Component\String\Slugger\SluggerInterface;
use App\WebContent\Trip as WebContentTrip;

class TripListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $syliusTranslator;

    protected $tripDataAction;

    protected $metaDataService;
    
    protected $slugger;

    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , SyliusTranslator $syliusTranslator
        , TripDataAction $tripDataAction
        , MetaData $metaDataService
        , SluggerInterface $slugger
        , WebContentTrip $webContentTripService
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->syliusTranslator = $syliusTranslator;
        $this->tripDataAction = $tripDataAction;
        $this->metaDataService = $metaDataService;
        $this->webContentTripService = $webContentTripService;
        $this->slugger = $slugger;

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        if ($entity->getLocale() !== $this->container->getParameter('locale')) {
           
            $translatedData = $this->translate($entity);
            
            if (!empty($translatedData)) {
              
                $this->tripDataAction->hydrate(
                    $translatedData,
                    $entity->getTranslatable(),
                    $entity->getLocale()
                );
            }
        }
        // dump($entity);
        // dump($entity->getLocale());
        // dump($entity->getTranslatable());
        // die;
        $entity = $this->enrich($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        $entity = $this->enrich($entity);
    }

    private function enrich($entity)
    {
        $this->createSlug($entity);
        $this->webContentTripService->moreData($entity);
        if(empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline() . ' ' . $entity->getTranslatable()->getArrivalTime()->format('Y-m-d'));
        }
        
        $this->webContentSEOService->defineMetaData($entity);
        $metaData = $this->metaDataService->getData($entity);
        $this->webContentSEOService->defineStructuredData($metaData, $entity);
        

        return $entity;
    }

    private function translate($entity)
    {
        $serializer = $this->container->get('serializer');
        $form = $this->container->get('form.factory')->create(TripTranslationType::class);
        $currentData = $serializer->normalize($entity, null);
        $referenceData = $serializer->normalize(
            $entity->getTranslatable()->getTranslation($this->container->getParameter('locale')), 
            null
        );

        if(!empty($referenceData['components'])) {
            $translatedComponents = $this->syliusTranslator->translateComponents($currentData, $referenceData, $entity->getLocale());
            $entity->setComponents(json_encode($translatedComponents));
        }

        


        return $this->syliusTranslator->translateEntity($currentData, $referenceData, $form, $entity->getLocale());
    }

    public function createSlug($entity)
    {
        $slug = $this->slugger->slug($entity->getHeadline() . ' ' . $entity->getTranslatable()->getArrivalTime()->format('Y-m-d'))->lower()->toString();
        
        $entity->setSlug($slug);

        return $entity;
    }
}