<?php

namespace App\Form\DataTransformer;

use App\Entity\WebPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class WebPageTransformer implements DataTransformerInterface
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

        $webPage = $this->manager
            ->getRepository(WebPage::class)
            ->find($data['id'])
        ;

        return $webPage;
    }

    public function reverseTransform($webPage)
    {
        if (null === $webPage) {
            return '';
        }

        return [
            'id' => $webPage->getId(),
            'slug' => $webPage->getSlug()
        ];
    }
}