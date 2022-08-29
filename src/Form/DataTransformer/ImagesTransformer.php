<?php

namespace App\Form\DataTransformer;

use App\Entity\ImageMediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class ImagesTransformer implements DataTransformerInterface
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
        
        $images = [];
        foreach($data as $value) {
            $image = $this->manager
                ->getRepository(ImageMediaObject::class)
                ->find($value['id'])
            ;
            $images[] = $image;
        }

        return $images;
    }

    public function reverseTransform($images)
    {
        
        if (null === $images) {
            return '';
        }

        $array = [];
        foreach($images as $image) {
            $array[] = [
                'id' => $image->getId(),
                'filename' => $image->getFilename(),
                'alt' => $image->getAlt(),
                'icon' => $image->getIcon()
            ];
        }
        
        return $array;
    }
}