<?php

namespace App\Data;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use Symfony\Component\String\Slugger\SluggerInterface;


class ArticleAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function create($data = [], $locale = 'fr_FR')
    {
        $article = new Article();
        $article->setCurrentLocale($locale);
        $articleTranslation = new ArticleTranslation();
        $article->addTranslation($articleTranslation); 
        $article = $this->hydrate($data, $article, $locale);
       
        return $article;
    }

    public function hydrate($data, $article, $locale)
    {
        $article->getTranslation()->setLocale($locale);
        $article->getTranslation()->setTranslatable($article);

        if(isset($data['headline'])){
            $article->getTranslation($locale)->setHeadline($data['headline']);
        }

        if(isset($data['category'])){
            $article->setCategory($data['category']);
        }
        
        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $article->addCategory($category);
            }
        }

        if(isset($data['primaryImage']) && !empty($data['primaryImage'])){
            $article->setPrimaryImage($data['primaryImage']);
        }

        if(isset($data['secondaryImage'])){
            $article->setSecondaryImage($data['secondaryImage']);
        }

        if(isset($data['alternativeHeadline'])){
            $article->getTranslation($locale)->setAlternativeHeadline($data['alternativeHeadline']);
        }

        if(isset($data['articleBody'])){
            $article->getTranslation($locale)->setArticleBody($data['articleBody']);
        }

        if (isset($data['pushForward'])) {
            $article->getTranslation($locale)->setPushForward($data['pushForward']);
        }

        if(isset($data['textResume'])){
            $article->getTranslation($locale)->setTextResume($data['textResume']);
        }

        // if (isset($data['components'])) {
        //     foreach ($data['components'] as $value) {
        //         $component = $this->createComponentDemand($value);
        //         $this->entityManager->persist($component);
        //         $this->entityManager->flush();
        //         $article->addComponent($component);
        //     }
        // }

        return $article;
    }

}
