<?php

namespace App\Data\Action;

use App\Entity\PropertyValue;
use Symfony\Component\String\Slugger\SluggerInterface;


class PropertyValueAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(PropertyValue::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(PropertyValue::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new PropertyValue();
        }
        $entity = $this->hydrate($data, $entity);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function hydrate($data = [], $entity)
    {
        if(isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if(isset($data['value'])) {
            $entity->setValue($data['value']);
        }

        return $entity;
    }

}
