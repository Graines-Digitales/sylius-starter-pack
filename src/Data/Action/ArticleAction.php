<?php

namespace App\Data\Action;

use App\Entity\Article;
use App\Entity\MediaObjectImage;
use App\Entity\ArticleTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class ArticleAction
{
    private $slugger;

    private $entityManager;

    private $mediaObjectImageAction;

    private $categoryAction;
    
    private $componentAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , MediaObjectImageAction $mediaObjectImageAction
        , CategoryAction $categoryAction
        , ComponentAction $componentAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
        $this->categoryAction = $categoryAction;
        $this->componentAction = $componentAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(Article::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['_slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['headline']))->lower()->toString();
        $entity = $this->entityManager->getRepository(Article::class)
            ->findOneBySlug($slug)
        ;
        if (null === $entity) {
            $entity = new Article();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new ArticleTranslation();
            $entity->addTranslation($entityTranslation);
        }
        $entity = $this->hydrate($data, $entity, $locale);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function hydrate($data, $entity, $locale)
    {
        // $entity->getTranslation()->setLocale($locale);
        // $entity->getTranslation()->setTranslatable($entity);

        if(isset($data['headline'])){
            $entity->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form ArticleAction relation field category');
            }
            $entity->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form ArticleAction relation field tags');
                }
                $entity->addTag($category);
            }
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['primaryImage']);
            $entity->setPrimaryImage($image);
        }

        if(isset($data['secondaryImage']) && !empty($data['secondaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['secondaryImage']);
            $entity->setSecondaryImage($image);
        }

        if(isset($data['alternativeHeadline'])){
            $entity->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['articleBody'])){
            $entity->getTranslation($locale)->setArticleBody($data['articleBody']);
        }

        if (isset($data['pushForward'])) {
            $entity->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $entity->getTranslation($locale)->setTextResume($data['textResume']);
        }

        if(isset($data['isLocked'])){
            $entity->setIsLocked($data['isLocked']);
        }

        if (isset($data['components']) && !empty($data['components']) && null !== $data['components']) {
            $components = $this->componentAction->createComponents($data['components']);
            $entity->getTranslation($locale)->setComponents(
                json_encode($components, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }

        if (isset($data['structuredData']) && !empty($data['structuredData'])) {
            $entity->getTranslation($locale)->setStructuredData($data['structuredData']);
        }
        
        return $entity;
    }

}
