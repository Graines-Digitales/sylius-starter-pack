<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * The mailing address.
 *
 * @see https://schema.org/PostalAddress
 * 
 * @ORM\Table(name="app_address")
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalStreetAddress;


    /**
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="addresses")
     */
    private $persons;

    /**
     * @ORM\ManyToMany(targetEntity="Organization", mappedBy="addresses")
     */
    private $organizations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $streetAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postOfficeBoxNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $addressRegion;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $addressLocality;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $addressCountry;



    /**
     * Constructor.
     */
    public function __construct()
    {
        // $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFullAddress();
    }

    public function getFullAddress()
    {
        return trim($this->getStreetAddress().' '.$this->getPostalCode().' '.$this->getAddressLocality().' '.$this->getAddressCountry());
    }

    /**
     * @param Person $person
     */
    public function addPerson($person)
    {
        if ($this->persons->contains($person)) {
            return;
        }
        $this->persons->add($person);
        $person->addStreetAddress($this);
    }

    /**
     * @param Person $person
     */
    public function removePerson($person)
    {
        if (!$this->persons->contains($person)) {
            return;
        }
        $this->persons->removeElement($person);
        $person->removeAddress($this);
    }

    /**
     * @param Organization $organization
     */
    public function addOrganization($organization)
    {
        if ($this->organizations->contains($organization)) {
            return;
        }
        $this->organizations->add($organization);
        // $organization->addOrganization($this);
    }

    /**
     * @param Organization $organization
     */
    public function removeOrganization($organization)
    {
        if (!$this->organizations->contains($organization)) {
            return;
        }
        $this->organizations->removeElement($organization);
        // $organization->removeOrganization($this);
    }

    /**
     * Set the value of Persons.
     *
     * @param mixed persons
     *
     * @return self
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;

        return $this;
    }

    /**
     * Get the value of Persons.
     *
     * @return mixed
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * Set the value of Organizations.
     *
     * @param mixed organizations
     *
     * @return self
     */
    public function setOrganizations($organizations)
    {
        $this->organizations = $organizations;

        return $this;
    }

    /**
     * Get the value of Organizations.
     *
     * @return mixed
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    public function getAdditionalStreetAddress(): ?string
    {
        return $this->additionalStreetAddress;
    }

    public function setAdditionalStreetAddress(?string $additionalStreetAddress): self
    {
        $this->additionalStreetAddress = $additionalStreetAddress;

        return $this;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPostOfficeBoxNumber(): ?string
    {
        return $this->postOfficeBoxNumber;
    }

    public function setPostOfficeBoxNumber(?string $postOfficeBoxNumber): self
    {
        $this->postOfficeBoxNumber = $postOfficeBoxNumber;

        return $this;
    }

    public function getAddressRegion(): ?string
    {
        return $this->addressRegion;
    }

    public function setAddressRegion(?string $addressRegion): self
    {
        $this->addressRegion = $addressRegion;

        return $this;
    }

    public function getAddressLocality(): ?string
    {
        return $this->addressLocality;
    }

    public function setAddressLocality(?string $addressLocality): self
    {
        $this->addressLocality = $addressLocality;

        return $this;
    }

    public function getAddressCountry(): ?string
    {
        return $this->addressCountry;
    }

    public function setAddressCountry(?string $addressCountry): self
    {
        $this->addressCountry = $addressCountry;

        return $this;
    }
}
