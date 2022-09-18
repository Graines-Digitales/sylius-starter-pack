<?php

namespace App\Data;

use App\Entity\Address;
use Symfony\Component\String\Slugger\SluggerInterface;


class AddressAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function create($data = [])
    {
        $address = new Address();
        $address = $this->hydrate($data, $address);

        return $address;
    }

    public function hydrate($data = [], $address)
    {
        $address->setAddress($data['streetAddress']);
        $address->setCity($data['addressLocality']);
        $address->setPostcode($data['postalCode']);

        if(isset($data['addressCountry'])) {
            $address->setCountry($data['addressCountry']);
        }

        if(isset($data['phone'])) {
            $address->setPhone($data['phone']);
        }

        return $address;
    }
}
