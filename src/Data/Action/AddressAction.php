<?php

namespace App\Data\Action;

use App\Entity\Address;
use Symfony\Component\String\Slugger\SluggerInterface;


class AddressAction
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
                ->getRepository(Address::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug($data['name'])->lower()->toString();
        $entity = $this->entityManager->getRepository(Address::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new Address();
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
        $entity->setAddress($data['streetAddress']);
        $entity->setCity($data['addressLocality']);
        $entity->setPostcode($data['postalCode']);

        if(isset($data['addressCountry'])) {
            $entity->setCountry($data['addressCountry']);
        }

        if(isset($data['phone'])) {
            $entity->setPhone($data['phone']);
        }

        return $entity;
    }
}
