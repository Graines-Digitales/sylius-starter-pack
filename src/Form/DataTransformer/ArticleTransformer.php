<?php

namespace App\Form\DataTransformer;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class ArticleTransformer implements DataTransformerInterface
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
            ->getRepository(Article::class)
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