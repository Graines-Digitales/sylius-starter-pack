<?php

namespace App\EventListener;

use App\Data\TripAction as TripDataAction;
use App\Entity\TripTranslation;
use App\Form\Type\TripTranslationType;
use App\Translation\SyliusTranslator;
use App\WebContent\SEO;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TripListener
{
    protected $container;
    
    protected $webContentSEOService;

    protected $syliusTranslator;

    protected $tripDataAction;

    
    public function __construct(
        ContainerInterface $container
        , SEO $webContentSEOService
        , SyliusTranslator $syliusTranslator
        , TripDataAction $tripDataAction
    )
    {
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->syliusTranslator = $syliusTranslator;
        $this->tripDataAction = $tripDataAction;

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        if($entity->getLocale() == 'en_GB') {
            
            $serializer = $this->container->get('serializer');
            $form = $this->container->get('form.factory')->create(TripTranslationType::class);
            $currentData = $serializer->normalize($entity, null);
            $referenceData = $serializer->normalize(
                $entity->getTranslatable()->getTranslation('fr_FR'), 
                null
            );

            $currentData = $this->syliusTranslator->translateEntity($currentData, $referenceData, $form);
            $this->tripDataAction->hydrate($currentData, $entity->getTranslatable(), 'en_GB');
        }

        $this->webContentSEOService->defineMetaData($entity);
        
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof TripTranslation) {
            return;
        }

        $this->webContentSEOService->defineMetaData($entity);
       
    }
   
}