<?php

namespace App\Form\DataTransformer;

use App\Entity\Article;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class MediaObjectTransformer implements DataTransformerInterface
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

        $media = $this->manager
            ->getRepository(MediaObject::class)
            ->find($data['id'])
        ;
        
        return $media;
    }

    public function reverseTransform($media)
    {
        if (null === $media) {
            return '';
        }

        return [
            'id' => $media->getId(),
            'filename' => $media->getFilename(),
            'alt' => $media->getAlt(),
            'icon' => $media->getIcon()

        ];
    }
}