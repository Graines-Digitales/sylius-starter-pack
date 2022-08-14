<?php

namespace App\WebContent;


class Product extends AbstractWebContent
{
    public function getData($category, $additionalType)
    {
        $category = $this->manager->getRepository(\App\Entity\Tag::class)
            ->findOneBy(['slug' => $category]);

        $additionalType = $this->manager->getRepository(\App\Entity\Tag::class)
            ->findOneBy(['slug' => $additionalType]);

        return $this->manager->getRepository(\App\Entity\Care::class)
            ->findBy([
                'category' => $category,
                'additionalType' => $additionalType
            ]
        );
    }

    public function getProductBySlug($slug)
    {
        return $this->manager->getRepository(\App\Entity\Care::class)
            ->findOneBy([
                'slug' => $slug
            ]
        );
    }

    public function moreData($entity) 
    {
        // if (empty($entity->getAlternativeHeadline())) {
        //     $entity->setAlternativeHeadline($entity->getHeadline());
        // }
        // if (empty($entity->getArticleResume())) {
        //     $resume = strip_tags($entity->getArticleBody());
        //     $resume = substr($resume, 0, 350);
        //     $resume = html_entity_decode($resume, ENT_QUOTES);
        //     $entity->setArticleResume(trim($resume));
        // }
    }
}
