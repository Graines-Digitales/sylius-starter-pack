<?php

namespace App\Data;

use App\Entity\Person;
use Symfony\Component\String\Slugger\SluggerInterface;


class PersonAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function create($data = [])
    {
        $person = new Person();
        $person = $this->hydrate($data, $person);

        return $person;
    }

    public function hydrate($data = [], $person)
    {
        $person->setLastname($data['lastname']);
        $person->setFirstname($data['firstname']);
        if(isset($data['email'])) {
            $person->setEmail($data['email']);
        }
        if(isset($data['phone'])) {
            $person->setPhone($data['phone']);
        }
        if(isset($data['entreprise'])) {
            $person->setOrganization($data['entreprise']);
        }
        if(isset($data['collaborateur'])) {
            $person->setNumberOfEmployees($data['collaborateur']);
        }
        if(isset($data['site_web'])) {
            $person->setUrl($data['site_web']);
        }
        if(isset($data['optin'])) {
            $person->setOptin($data['optin']);
        }
        if(isset($data['gender'])) {
            $person->setGender($data['gender']);
        }

        return $person;
    }

}
