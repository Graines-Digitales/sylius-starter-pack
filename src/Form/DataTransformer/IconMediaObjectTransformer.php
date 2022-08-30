<?php

namespace App\Form\DataTransformer;

use App\Entity\IconMediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class IconMediaObjectTransformer implements DataTransformerInterface
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
            ->getRepository(IconMediaObject::class)
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
            'alt' => $media->getCaption()
        ];
    }
}