<?php

namespace App\Data\Action;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class PersonAction
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
        $entity = null;
        if(isset($data['email'])) {
            $entity = $this->entityManager
                ->getRepository(Person::class)
                ->findOneBy([ 'email' => $data['email'] ])
            ;
        }
        if(null === $entity) {
            $entity = new Person();
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
        $entity->setLastname($data['lastname']);
        $entity->setFirstname($data['firstname']);
        if(isset($data['email'])) {
            $entity->setEmail($data['email']);
        }
        if(isset($data['phone'])) {
            $entity->setPhone($data['phone']);
        }
        if(isset($data['entreprise'])) {
            $entity->setOrganization($data['entreprise']);
        }
        if(isset($data['collaborateur'])) {
            $entity->setNumberOfEmployees($data['collaborateur']);
        }
        if(isset($data['site_web'])) {
            $entity->setUrl($data['site_web']);
        }
        if(isset($data['optin'])) {
            $entity->setOptin($data['optin']);
        }
        if(isset($data['gender'])) {
            $entity->setGender($data['gender']);
        }

        return $entity;
    }

}
