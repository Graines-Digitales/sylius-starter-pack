<?php

namespace App\Form\DataTransformer;

use App\Entity\MediaObjectVideo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class MediaObjectVideoTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($data)
    {
        if (!$data) {
            return;
        }

        $media = $this->entityManager
            ->getRepository(MediaObjectVideo::class)
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
            'name' => $media->getName()
        ];
    }
}