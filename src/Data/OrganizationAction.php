<?php

namespace App\Data;

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

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , AddressAction $addressAction
    ){
        $this->slugger = $slugger;
        $this->addressAction = $addressAction;
        $this->entityManager = $entityManager;
    }

    public function create($data = [])
    {
        $organization = new Organization();
        $organization = $this->hydrate($data, [], $organization);
        
        return $organization;
    }
    
    public function hydrate($data = [], $addresses = [], $organization)
    {
        if(isset($data['category'])) {
            $category = $this->entityManager->getRepository(Category::class)
                ->findOneBySlug($data['category']);
            $organization->setCategory($category);
        }
        if(isset($data['name'])) {
            $organization->setName($data['name']);
        }
        if(isset($data['legal_name'])) {
            $organization->setLegalName($data['legal_name']);
        }
        if(isset($data['phone'])) {
            $organization->setPhone($data['phone']);
        }
        if(isset($data['mobile_phone'])) {
            $organization->setMobilePhone($data['mobile_phone']);
        }
        if(isset($data['url'])) {
            $organization->setUrl($data['url']);
        }
        if(isset($data['email'])) {
            $organization->setEmail($data['email']);
        }
        if(isset($data['founding_date'])) {
            $date = new \DateTime($data['founding_date']);
            $organization->setFoundingDate($data['founding_date']);
        }
        if(isset($data['number_of_employees'])) {
            $organization->setNumberOfEmployees($data['number_of_employees']);
        }
        if(isset($data['number_of_projects'])) {
            $organization->setNumberOfProjects($data['number_of_projects']);
        }

        foreach ($addresses as $address) {
            $address = $this->addressAction->create($address);
            $organization->addAddress($address);
        }

        if(isset($data['socials'])) {
            foreach ($data['socials'] as $key => $result) {
                $slug = $this->slugger->slug($key)->lower()->toString();
                $socialLink = $this->entityManager->getRepository(Organization::class)
                    ->findOneBy(['slug' => $slug]);
                if(null !== $socialLink) {
                    $organization->addSocialLink($socialLink);
                }
            }
        }

        if(isset($data['identifier'])) {
            foreach ($data['identifier'] as $key => $result) {
                $identifier = new PropertyValue();
                $identifier->setName($result['name']);
                $identifier->setValue($result['value']);
                $organization->addIdentifier($identifier);
            }
        }

        if(isset($data['openingHoursSpecification'])) {

            foreach ($data['openingHoursSpecification'] as $k=>$v) {
                foreach ($v as $key => $value) {
                    $property = new PropertyValue();
                    $property->setName($key);
                    if(is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    $property->setValue($value);
                    $property->setValueReference('openingHoursSpecification');
                    $organization->addOpeningHour($property);
                }
            }
        }

        return $organization;
    }
}
