<?php

namespace App\Data\Action;

use App\Entity\Person;
use App\Data\Action\AddressAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class PersonAction
{
    private $slugger;

    private $entityManager;

    private $addressAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , AddressAction $addressAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->addressAction = $addressAction;
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
        if(isset($data['streetAddress'])) {
            $address = $this->addressAction->create($data);
            $entity->addAddress($address);
        }
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
            
                $address = $this->addressAction->create($address);
                if(null === $address) {
                    throw new \Exception('Error form OrganizationAction relation field addresses');
                }
                $entity->addAddress($address);
            }
        }

        return $entity;
    }

}
