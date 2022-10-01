<?php

namespace App\Data;

use App\Entity\WebPage;
use App\Entity\ImageMediaObject;
use App\Entity\WebPageTranslation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class WebPageAction
{
    private $slugger;

    private $entityManager;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function create($data = [], $locale = 'fr_FR')
    {
        $webPage = new WebPage();
        $webPage->setCurrentLocale($locale);
        $webPageTranslation = new WebPageTranslation();
        $webPage->addTranslation($webPageTranslation); 
        $webPage = $this->hydrate($data, $webPage, $locale);
       
        return $webPage;
    }

    public function hydrate($data, $webPage, $locale)
    {   
        $webPage->getTranslation()->setLocale($locale);
        $webPage->getTranslation()->setTranslatable($webPage);

        if (isset($data['isLocked'])) {
            $webPage->setIsLocked($data['isLocked']);
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            if(!$data['primaryImage'] instanceof ImageMediaObject) {
                $primaryImage = $this->entityManager->getRepository(ImageMediaObject::class)
                ->findOneBy(['filename' => $data['primaryImage'] ]);
                if(null !== $primaryImage) {
                    $webPage->setPrimaryImage($primaryImage);
                }
            } else {
                $webPage->setPrimaryImage($data['primaryImage']);
            }
        }

        if (isset($data['metaTitle'])) {
            $webPage->getTranslation($locale)->setMetaTitle($data['metaTitle']);
        }

        if (isset($data['metaDescription'])) {
            $webPage->getTranslation($locale)->setMetaDescription($data['metaDescription']);
        }

        if (isset($data['headline'])) {
            $webPage->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['alternativeHeadline'])){
           
            $webPage->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['text'])){
            $webPage->getTranslation($locale)->setText($data['text']);
        }

        if (isset($data['pushForward'])) {
            $webPage->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $webPage->getTranslation($locale)->setTextResume($data['textResume']);
        }
        
        // $components = '{}';
        // if (isset($data['components'])) {
        //     $components = $data['components'];
        // }
        // $webPage->setComponents($components, 'fr_FR');

        return $webPage;
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
