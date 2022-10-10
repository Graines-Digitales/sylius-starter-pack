<?php

namespace App\Data\Action;

use App\Entity\WebPage;
use App\Entity\MediaObjectImage;
use App\Entity\WebPageTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class WebPageAction
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
                ->getRepository(WebPage::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['headline'])->lower()->toString();
        $entity = $this->entityManager
            ->getRepository(WebPage::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new WebPage();
            $entity->setCurrentLocale($locale);
            $entityTranslation = new WebPageTranslation();
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
        $entity->getTranslation()->setLocale($locale);
        $entity->getTranslation()->setTranslatable($entity);

        if (isset($data['isLocked'])) {
            $entity->setIsLocked($data['isLocked']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['primaryImage']);
            $entity->setPrimaryImage($image);
        }

        if(isset($data['secondaryImage']) && !empty($data['secondaryImage'])){
            $image = $this->mediaObjectImageAction->extract($data['secondaryImage']);
            $entity->setSecondaryImage($image);
        }

        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form WebPageAction relation field category');
            }
            $entity->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form WebPageAction relation field tags');
                }
                $entity->addTag($category);
            }
        }

        if (isset($data['metaTitle'])) {
            $entity->getTranslation($locale)->setMetaTitle($data['metaTitle']);
        }

        if (isset($data['metaDescription'])) {
            $entity->getTranslation($locale)->setMetaDescription($data['metaDescription']);
        }

        if (isset($data['headline'])) {
            $entity->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['alternativeHeadline'])){
           
            $entity->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['text'])){
            $entity->getTranslation($locale)->setText($data['text']);
        }

        if (isset($data['pushForward'])) {
            $entity->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $entity->getTranslation($locale)->setTextResume($data['textResume']);
        }
        
        if (isset($data['components']) && !empty($data['components'])) {
           
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

    public function createFromProduct($product)
    {
        $product['headline'] = $product['name'];
        $product['alternativeHeadline'] = $product['name'];
        $product['text'] = $product['description'];

        return $this->create($product);
    }

    public function createFromCategory($category)
    {
        if(empty($category['translation']['headline'])) {
            $category['translation']['headline'] = $category['translation']['name'];
        }
        if(empty($category['translation']['alternativeHeadline'])) {
            $category['translation']['alternativeHeadline'] = $category['name'];
        }
        if(empty($category['translation']['text'])) {
            $category['translation']['text'] = $category['translation']['description'];
        }

        return $this->create($category);
    }

    public function createFromArticle($article)
    {
        if(empty($article['translation']['text'])) {
            $article['translation']['text'] = $article['translation']['articleBody'];
        }

        if(empty($article['translation']['textResume'])) {
            $article['translation']['text'] = $article['translation']['articleResume'];
        }

        return $this->create($article);
    }
}
