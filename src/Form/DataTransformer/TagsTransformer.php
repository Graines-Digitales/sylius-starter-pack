<?php

namespace App\Form\DataTransformer;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class TagsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function transform($data)
    {
        if (!$data) {
            return;
        }
        
        $tags = [];
        
        foreach($data as $key=>$value) {
       
            if(is_bool($value)) {
                if(true === $value) {
                    $tag = $this->manager
                        ->getRepository(Category::class)
                        ->find($key)
                    ;
                    $tags[] = $tag;
                }
            } else {
                $tag = $this->manager
                    ->getRepository(Category::class)
                    ->find($value['id'])
                ;
                $tags[] = $tag;
            }
            
            
        }

        return $tags;
    }

    public function reverseTransform($tags)
    {
        
        if (null === $tags) {
            return '';
        }

        $array = [];
        foreach($tags as $tag) {
            $array[] = [
                'id' => $tag->getId(),
                'slug' => $tag->getSlug()
            ];
        }
        
        return $array;
    }
}