<?php

namespace App\Data\Action;

use App\Entity\Category;
use App\Entity\Organization;
use App\Entity\PropertyValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class OrganizationAction
{
    private $slugger;

    private $entityManager;

    private $addressAction;

    private $categoryAction;

    private $propertyValueAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , AddressAction $addressAction
        , CategoryAction $categoryAction
        , PropertyValueAction $propertyValueAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->addressAction = $addressAction;
        $this->categoryAction = $categoryAction;
        $this->propertyValueAction = $propertyValueAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(Organization::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['name']))->lower()->toString();
        $entity = $this->entityManager
            ->getRepository(Organization::class)
            ->findOneBySlug($slug)
        ;
        if(null === $entity) {
            $entity = new Organization();
        }
        $entity = $this->hydrate($data, [], $entity);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }
    
    public function hydrate($data = [], $addresses = [], $entity)
    {
        if(isset($data['category']) && !empty($data['category'])){
            $array = explode( '\\', get_class($entity));
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form OrganizationAction relation field category');
            }
            $entity->setCategory($data['category']);
        }
        if(isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if(isset($data['legal_name'])) {
            $entity->setLegalName($data['legal_name']);
        }
        if(isset($data['phone'])) {
            $entity->setPhone($data['phone']);
        }
        if(isset($data['mobile_phone'])) {
            $entity->setMobilePhone($data['mobile_phone']);
        }
        if(isset($data['url'])) {
            $entity->setUrl($data['url']);
        }
        if(isset($data['email'])) {
            $entity->setEmail($data['email']);
        }
        if(isset($data['founding_date'])) {
            $date = new \DateTime($data['founding_date']);
            $entity->setFoundingDate($data['founding_date']);
        }
        if(isset($data['number_of_employees'])) {
            $entity->setNumberOfEmployees($data['number_of_employees']);
        }
        if(isset($data['number_of_projects'])) {
            $entity->setNumberOfProjects($data['number_of_projects']);
        }

        if (isset($data['addresses'])) {
            foreach ($addresses as $address) {
                $address = $this->addressAction->create($address);
                if(null === $address) {
                    throw new \Exception('Error form OrganizationAction relation field addresses');
                }
                $entity->addAddress($address);
            }
        }

        if(isset($data['socials'])) {
            foreach ($data['socials'] as $key => $result) {
                $result = $this->create($result);
                if(null === $result) {
                    throw new \Exception('Error form OrganizationAction relation field socials');
                }
                $entity->addSocialLink($result);
            }
        }

        if(isset($data['identifier'])) {
            foreach ($data['identifier'] as $key => $result) {
                $identifier = $this->propertyValueAction->create($data['identifier'] );
                if(null === $identifier) {
                    throw new \Exception('Error form OrganizationAction relation field identifier');
                }
                $entity->addIdentifier($identifier);
            }
        }

        if(isset($data['openingHoursSpecification'])) {

            foreach ($data['openingHoursSpecification'] as $k=>$v) {
                foreach ($v as $key => $value) {

                    $property = $this->propertyValueAction->create($value);
                    if(null === $property) {
                        throw new \Exception('Error form OrganizationAction relation field openingHoursSpecification');
                    }
                 

                    // $property = new PropertyValue();
                    // $property->setName($key);
                    // if(is_array($value)) {
                    //     $value = implode(', ', $value);
                    // }
                    // $property->setValue($value);
                    // $property->setValueReference('openingHoursSpecification');
                    $entity->addOpeningHour($property);
                }
            }
        }

        return $entity;
    }
}
