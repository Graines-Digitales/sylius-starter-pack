<?php

namespace App\Form\DataTransformer;

use App\Entity\PropertyValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class PropertyValueTransformer implements DataTransformerInterface
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

        $article = $this->manager
            ->getRepository(PropertyValue::class)
            ->find($data['id'])
        ;

        return $article;
    }

    public function reverseTransform($article)
    {
        if (null === $article) {
            return '';
        }

        return [
            'id' => $article->getId(),
            'slug' => $article->getSlug()
        ];
    }
}