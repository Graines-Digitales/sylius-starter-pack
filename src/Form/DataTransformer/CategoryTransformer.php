<?php

namespace App\Form\DataTransformer;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class CategoryTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function transform($data)
    {
        // dump($data);die;
        if (!isset($data['slug'])) {
            return;
        }

        $category = $this->manager
            ->getRepository(Category::class)
            ->find($data['slug'])
        ;

        return $category;
    }

    public function reverseTransform($category)
    {
       
        if (null === $category) {
            return '';
        }

        return ['slug' => $category];
    }
}