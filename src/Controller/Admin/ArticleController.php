<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;

class ArticleController extends ResourceController
{
    public function duplicateAction(Request $request): Response
    {         
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);
        $newResource = clone $resource;
        $newResource->setId(null);
        $this->manager->persist($newResource);
        $this->manager->flush();
        foreach($newResource->getTranslations()->getValues() as $translation) {
            $newTranslation = clone $translation;
            $newTranslation->setId(null);
            $newTranslation->setSlug(null);
            $newTranslation->setTranslatable($newResource);
            $this->manager->persist($newTranslation);
            $this->manager->flush();
        }

        if ($configuration->isHtmlRequest()) {
            $this->flashHelper->addSuccessFlash($configuration, ResourceActions::CREATE, $newResource);
        }

        return $this->redirectHandler->redirectToReferer($configuration);
    }
    
}