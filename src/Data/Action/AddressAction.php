<?php

namespace App\Data\Action;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class AddressAction
{
    private $slugger;

    private $entityManager;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(Address::class)
                ->findOneBySlug($data)
            ;
        }

        $entity = $this->entityManager->getRepository(Address::class)
            ->findOneBy(["name" => $data['name'] ])
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
        if(isset($data['name'])) {
            $entity->setName($data['name']);
        }

        if(isset($data['city'])) {
            $entity->setCity($data['city']);
        }

        if(isset($data['address'])) {
            $entity->setAddress($data['address']);
        }

        if(isset($data['postcode'])) {
            $entity->setPostcode($data['postcode']);
        }

        if(isset($data['country'])) {
            $entity->setCountry($data['country']);
        }

        if(isset($data['additionalStreetAddress'])) {
            $entity->setAdditionalStreetAddress($data['additionalStreetAddress']);
        }

        return $entity;
    }
}
