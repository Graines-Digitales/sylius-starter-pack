<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebPageController extends ResourceController
{
    public function indexAction(Request $request): Response
    {            
       
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $this->eventDispatcher->dispatchMultiple(ResourceActions::INDEX, $configuration, $resources);
        
        // dump($configuration);
        // dump($resources);
        // dump($this->metadata);
        // dump('test override adminController');die;

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate(ResourceActions::INDEX . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resources' => $resources,
                $this->metadata->getPluralName() => $resources,
            ]);
        }

        return $this->createRestView($configuration, $resources);
    }
}