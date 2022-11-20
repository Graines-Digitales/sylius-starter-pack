<?php

namespace App\EventListener;

use App\Data\Action\ComponentAction;
use App\Entity\WebPageTranslation;
use App\Entity\ComponentTranslation;
use App\Translation\SyliusTranslator;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\ComponentTranslationType;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\WebContent\Component as WebContentComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ComponentListener
{
    private $container;
    
    private $webContentComponentService;
    
    private $entityManager;

    private $syliusTranslator;

    private $componentDataAction;

    public function __construct(
        ContainerInterface $container
        , WebContentComponent $webContentComponentService
        , EntityManagerInterface $entityManager
        , SyliusTranslator $syliusTranslator
        , ComponentAction $componentDataAction
    ){
        $this->container = $container;
        $this->webContentComponentService = $webContentComponentService;
        $this->entityManager = $entityManager;
        $this->syliusTranslator = $syliusTranslator;
        $this->componentDataAction = $componentDataAction;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ComponentTranslation) {
            return;
        }

        if ($entity->getLocale() !== $this->container->getParameter('locale')) {
            $translatedData = $this->translate($entity);
            if (!empty($translatedData)) {
                $this->componentDataAction->hydrate(
                    $translatedData,
                    $entity->getTranslatable(),
                    $entity->getLocale()
                );
            }
        }

        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentComponentService->updateSlug($entity);  
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ComponentTranslation) {
            return;
        }
        
        if(false === $entity->getTranslatable()->getIsLocked()) {
            $entity = $this->webContentComponentService->updateSlug($entity);  
        }
    }

    private function translate($entity)
    {
        $serializer = $this->container->get('serializer');
        $form = $this->container->get('form.factory')->create(ComponentTranslationType::class);
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

}