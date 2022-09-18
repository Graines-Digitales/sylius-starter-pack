<?php

namespace App\Data;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use Symfony\Component\String\Slugger\SluggerInterface;


class CategoryAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function create($data = [], $locale = 'fr_FR')
    {
        $category = new Category();
        $category->setCurrentLocale($locale);
        $categoryTranslation = new CategoryTranslation();
        $category->addTranslation($categoryTranslation); 
        $category = $this->hydrate($data, $category, $locale);

        return $category;
    }
    
    public function hydrate($data, $category, $locale) 
    {
        $category->getTranslation()->setLocale($locale);
        if(isset($data['name'])) {
            $category->getTranslation()->setName($data['name']);
        }
        if(isset($data['type']) && true == $data['type']) {
            $category->setType($data['slug']);
        }
        $category->getTranslation()->setTranslatable($category);
      
        return $category;
    }

 

}
