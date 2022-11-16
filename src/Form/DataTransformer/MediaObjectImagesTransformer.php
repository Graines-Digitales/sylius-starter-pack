<?php

namespace App\Form\DataTransformer;

use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;


class MediaObjectImagesTransformer implements DataTransformerInterface
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
        foreach($data as $key=>$value) {
            if(is_bool($value)) {
                if(true === $value) {
                    $image = $this->manager
                        ->getRepository(MediaObjectImage::class)
                        ->find($key)
                    ;
                    $images[] = $image;
                }
            } else {
                $image = $this->manager
                    ->getRepository(MediaObjectImage::class)
                    ->find($value['id'])
                ;
                $images[] = $image;
            }
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
                'alt' => $image->getCaption(),
                'caption' => $image->getCaption(),
            ];
        }

        return $array;
    }
}