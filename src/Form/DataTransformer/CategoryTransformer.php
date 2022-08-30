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
        if (!$data) {
            return;
        }

        $category = $this->manager
            ->getRepository(Category::class)
            ->find($data['id'])
        ;

        return $category;
    }

    public function reverseTransform($category)
    {
       
        if (empty($category)) {
            return '';
        }

        return [
            'id' => $category->getId(),
            'slug' => $category->getSlug()
        ];
    }
}