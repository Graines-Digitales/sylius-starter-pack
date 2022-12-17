<?php

namespace App\WebContent;

use App\Entity\Organization;
use App\Entity\ArticleTranslation;
use App\Entity\WebPageTranslation;
use App\Entity\CategoryTranslation;
use App\Entity\TripTranslation;
use Doctrine\ORM\EntityManagerInterface;
use App\Tools\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SEO
{   
    private $container;

    private $manager;

    private $webContentStructuredDataService;
    
    private $contentTools;

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $manager
        , StructuredData $webContentStructuredDataService
        , Content $contentTools
    ){
        
        $this->container = $container;
        $this->manager = $manager;
        $this->webContentStructuredDataService = $webContentStructuredDataService;
        $this->contentTools = $contentTools;
    }

    public function defineMetaData($entity)
    {
       
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = $configurationProject['slug'];
        
        $organization = $this->manager->getRepository(Organization::class)
            ->findOneBy(['slug' => $slug]);
        $suffixe = (null === $organization)? ' - Nom du site': ' - ' . $organization->getName();



        if ($entity instanceof CategoryTranslation) {
            if(empty($entity->getMetaTitle())) {
                $metaTitle = ucfirst(substr($entity->getName(), 0, 50) . $suffixe);
                $entity->setMetaTitle($metaTitle);
            }
           
            if (empty($entity->getMetaDescription())) {
                $metaDescription = $this->contentTools->shapeSpace_truncate_string_at_word(
                    $entity->getDescription(),
                    150,
                    ' ',
                    ''
                );
                $entity->setMetaDescription($metaDescription);
            }
        } 
        else if ($entity instanceof ArticleTranslation) {
            if (empty($entity->getMetaTitle())) {
                $metaTitle = ucfirst(substr($entity->getHeadline(), 0, 50) . $suffixe);
                $entity->setMetaTitle($metaTitle);
            }
            if (empty($entity->getMetaDescription())) {
                $metaDescription = $this->contentTools->shapeSpace_truncate_string_at_word(
                    $entity->getArticleBody(),
                    150,
                    ' ',
                    ''
                );
                $entity->setMetaDescription($metaDescription);
            }
        } 
        else if ($entity instanceof WebPageTranslation) {
            if (empty($entity->getMetaTitle())) {
                $metaTitle = ucfirst(substr($entity->getHeadline(), 0, 50) . $suffixe);
                $entity->setMetaTitle($metaTitle);
            }
            if (empty($entity->getMetaDescription())) {
                $metaDescription = $this->contentTools->shapeSpace_truncate_string_at_word(
                    $entity->getText(),
                    150,
                    ' ',
                    ''
                );
                $entity->setMetaDescription($metaDescription);
            }
        } 
        else if ($entity instanceof TripTranslation) {
            if (empty($entity->getMetaTitle())) {
                $metaTitle = ucfirst(substr($entity->getAlternativeHeadline(), 0, 50) . $suffixe);
                $entity->setMetaTitle($metaTitle);
            }
            if (empty($entity->getMetaDescription())) {
                $metaDescription = $this->contentTools->shapeSpace_truncate_string_at_word(
                    $entity->getDescription(),
                    150,
                    ' ',
                    ''
                );
                $entity->setMetaDescription($metaDescription);
            }
        }

        return $entity;
    }

    public function defineAltImage($media)
    {
        return $media->getName();
    }

    
    public function defineStructuredData($metaData, $entity)
    {
        $structuredData = $this->webContentStructuredDataService->generate($metaData, $entity);
        $entity->setStructuredData($structuredData);
    }
}
