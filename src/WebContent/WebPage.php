<?php

namespace App\WebContent;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Channel\Channel;
use App\Entity\Product\Product;


class WebPage extends AbstractWebContent
{

    public function moreData($entity)
    {
        parent::moreData($entity);
    }
    // public function getData($slug)
    // {
    //     return $this->manager->getRepository(\App\Entity\WebPage::class)
    //         ->findOneBySlug($slug);
    // }

//     public function getDataFromCategory($slug)
//     {
//         $result = $this->manager->getRepository(Category::class)
//             ->findOneBySlug($slug);

//         $result = json_decode($this->serializer->serialize($result,'json'), true);

//         return $this->dataAction->createWebPageFromCategoryDemand($result);
//     }

//     public function getDataFromArticle($article)
//     {
//         // $result = $this->manager->getRepository(Article::class)
//         //     ->findOneBySlug($slug);
//         $result = json_decode($this->serializer->serialize($article,'json'), true);
// // dump($article);die;
//         return $this->dataAction->createWebPageFromArticleDemand($result);
//     }

//     public function getDataFromProduct($product)
//     {
//         $result = json_decode($this->serializer->serialize($product,'json'), true);
// // dump($result);die;
//         return $this->dataAction->createWebPageFromProductDemand($result);
//     }

    // public function getDataByCode($code)
    // {
    //     return $this->manager->getRepository(\App\Entity\WebPage::class)
    //         ->findOneByCode($code);
    // }

    // public function getArticles()
    // {
    //     return $this->manager->getRepository(Article::class)
    //         ->findAll();
    // }

    // public function getArticle($slug)
    // {
    //     return $this->manager->getRepository(Article::class)
    //         ->findOneBySlug($slug);
    // }

    // public function getProduct($slug)
    // {
    //     // $channel = $this->container->get('sylius.context.channel')->getChannel();
    //     $channel = $this->manager->getRepository(Channel::class)->findOneByCode('default');
    //     $locale = $this->container->getParameter('locale');
     
    //     return $this->manager->getRepository(Product::class)
    //         ->findOneByChannelAndSlug($channel, $locale, $slug);
    // }

    // public function getCategory($slug)
    // {
    //     return $this->manager->getRepository(Category::class)
    //         ->findOneBySlug($slug);
    // }

    // public function getPageSlug($key)
    // {
    //     $configurationProject = $this->container->getParameter('configuration_project'); 
        
    //     return $configurationProject['web_pages'][$key]['slug'];
    // }

    public function updateSlug($entity)
    {
        
        $slug = $this->slugger->slug($entity->getHeadline())->lower()->toString();
        
        $entity->setSlug($slug);

        return $entity;
    }

    // public function getAllPages()
    // {
    //     $webPages = $this->manager->getRepository(\App\Entity\WebPage::class)
    //     ->findBy(['isIndexed' => true]);


    //     return $webPages;
    // }

    // public function getAllCategoryPages()
    // {
    //     $webPages = $this->manager->getRepository(\App\Entity\Category::class)
    //     ->findBy(['isIndexed' => true]);


    //     return $webPages;
    // }
    
    // public function getCares()
    // {
    //     $cares = [];
    //     $results = $this->manager->getRepository(\App\Entity\Care::class)
    //     ->findAll();
    //     foreach($results as $result) {
    //         $cares[$result->getSlug()] = [
    //             'name' => $result->getName(),
    //             'category' => $result->getCategory()->getSlug(),
    //             'additional_type' => $result->getAdditionalType()->getSlug()
    //         ];
    //     }

    //     return $cares;
    // }



    // public function getCategoriesByParentSlug($slug)
    // {
    //     $parent = $this->manager->getRepository(\App\Entity\Tag::class)
    //         ->findOneBy(['slug' => $slug]);
    //     $categories = $this->manager->getRepository(\App\Entity\Tag::class)
    //         ->findBy(['parent' => $parent]);

    //     return $categories;
    // }

    // public function getArticles($slug = "plus-d-infos")
    // {
    //     return $this->manager->getRepository(\App\Entity\Article::class)
    //         ->findBy(['category' => $slug ]);
    // }

    // public function getArticle($slug)
    // {
    //     return $this->manager->getRepository(\App\Entity\Article::class)
    //         ->findOneBy(['slug' => $slug ]);
    // }

    // public function getComponents($componentsPage)
    // {
    //     /** @TODO : si la fonction doit s'étendre alors il faudra refacto */
    //     $results = $this->manager->getRepository(Component::class)->findAll();
    //     $components = [];
    //     foreach($results as $result) {
    //         if($result->getWebPages()->count() < 1) {
    //             $components[] = $this->getComponent($result);
    //         }
    //     }
    //     foreach($componentsPage as $result) {
    //         $components[] = $this->getComponent($result);
    //     }

    //     return $components;
    // }

    // private function getComponent($result)
    // {
    //     $data = [];
    //     if($result->getCategory()) {
    //         $data = $this->getDataComponent($result->getCategory()->getSlug());
    //     }

    //     return [
    //         'slug' => $result->getSlug(),
    //         'entity' => $result,
    //         'data' => $data
    //     ];
    // }

    // private function getDataComponent($slug)
    // {
    //     switch ($slug) {
    //         case 'last-articles':
    //                 $category = $this->manager->getRepository(Tag::class)
    //                     ->findBy([ 'slug' => 'blog', 'isActive' => true ]);

    //                 return $this->manager->getRepository(Article::class)
    //                     ->findBy([ 'category' => $category, 'isActive' => true ], [ 'id' => 'DESC', ], 3);
    //             break;

    //         case 'prestation-articles':
    //                 $category = $this->manager->getRepository(Tag::class)
    //                     ->findBy([ 'slug' => 'prestation', 'isActive' => true ]);

    //                 return $this->manager->getRepository(Article::class)
    //                     ->findBy([ 'category' => $category, 'isActive' => true ]);
    //             break;

    //         case 'project-articles':
    //                 $category = $this->manager->getRepository(Tag::class)
    //                     ->findBy([ 'slug' => 'project', 'isActive' => true ]);

    //                 return $this->manager->getRepository(Article::class)
    //                     ->findBy([ 'category' => $category, 'isActive' => true ]);
    //             break;

    //         case 'all-articles':
    //                 $category = $this->manager->getRepository(Tag::class)
    //                     ->findBy([ 'slug' => 'blog', 'isActive' => true ]);

    //                 return $this->manager->getRepository(Article::class)
    //                     ->findBy([ 'category' => $category, 'isActive' => true ], [ 'id' => 'DESC' ]);
    //             break;

    //         case 'last-article':
    //                 $category = $this->manager->getRepository(Tag::class)
    //                     ->findBy([ 'slug' => 'blog', 'isActive' => true ]);

    //                 return $this->manager->getRepository(Article::class)
    //                     ->findBy([ 'category' => $category, 'isActive' => true ], [ 'id' => 'DESC' ], 1);
    //             break;

    //         default:

    //             break;
    //     }

    //     return [];
    // }
}
