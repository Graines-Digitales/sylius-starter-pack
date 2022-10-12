<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/Person")
 * @ORM\Entity()
 * @ORM\Table(name="app_person")
 */
class Person implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true, options={"comment":"Firstname"})
     *
     * @Assert\NotBlank(
     *  message="Enter a first name please"
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, options={"comment":"Lastname"})
     * @Assert\NotBlank(
     *  message="Enter a name please"
     * )
     */
    private $lastname;

    /**
     * @ ORM\ManyToOne(targetEntity="Gender", inversedBy="persons")
     * @ ORM\JoinColumn(name="gender_id", referencedColumnName="id", nullable=true)
     *
     * @ Assert\NotBlank(message="Enter a gender please")
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date" , options={"comment":"Birthday"}, nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="place_of_birth", type="string", length=255, options={"comment":"Place of birth"}, nullable=true)
     */
    private $placeOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, options={"comment":"Phone"}, nullable=true)
     *
     * @ Assert\Expression(
     *     "this.getEmail() || this.getPhone()",
     *     message="Please, enter email or phone"
     * )
     *
     * @Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="This phone number is invalid"
     * )
     * @Assert\NotBlank(message="Enter a phone please")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, options={"comment":"Email"}, nullable=true)
     *
     * @Assert\Email(
     *  message = "This email is invalid"
     * )
     * @Assert\NotBlank(message="Enter an email please")
     */
    private $email;

    /**
     * @ ORM\ManyToMany(targetEntity="Address", inversedBy="persons", cascade= {"persist"})
     * @ ORM\JoinTable(
     *  name="app_persons_addresses",
     *  joinColumns={
     *      @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     *  }
     * )
     **/
    private $addresses;

    /**
     * @ORM\ManyToMany(targetEntity=Accommodation::class, mappedBy="teams")
     */
    private $accommodations;

    /**
     * @ORM\ManyToOne(targetEntity=Accommodation::class, inversedBy="owner")
     */
    private $accommodation;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="person")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=MediaObjectDocument::class, mappedBy="persons")
     */
    private $mediaObjectDocuments;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->accommodations = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->mediaObjectDocuments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getFullName()
    {
        return trim($this->getFirstName().' '.$this->getLastName());
    }

    public function setFullName($fullName)
    {
        $names = explode(' ', $fullName);
        $firstName = array_shift($names);
        $lastName = implode(' ', $names);

        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * @param string $placeOfBirth
     */
    public function setPlaceOfBirth($placeOfBirth)
    {
        $this->placeOfBirth = $placeOfBirth;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Add address.
     *
     * @param \App\Entity\Address $address
     *
     * @return Person
     */
    public function addAddress($address)
    {
        if ($this->addresses->contains($address)) {
            return;
        }

        $this->addresses->add($address);
    }

    /**
     * Remove address.
     *
     * @param \App\Entity\Address $address
     */
    public function removeAddress($address)
    {
        if (!$this->addresses->contains($address)) {
            return;
        }

        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return Collection<int, Accommodation>
     */
    public function getAccommodations(): Collection
    {
        return $this->accommodations;
    }

    public function addAccommodation(Accommodation $accommodation): self
    {
        if (!$this->accommodations->contains($accommodation)) {
            $this->accommodations[] = $accommodation;
            $accommodation->addTeam($this);
        }

        return $this;
    }

    public function removeAccommodation(Accommodation $accommodation): self
    {
        if ($this->accommodations->removeElement($accommodation)) {
            $accommodation->removeTeam($this);
        }

        return $this;
    }

    public function getAccommodation(): ?Accommodation
    {
        return $this->accommodation;
    }

    public function setAccommodation(?Accommodation $accommodation): self
    {
        $this->accommodation = $accommodation;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPerson($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getPerson() === $this) {
                $event->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaObjectDocument>
     */
    public function getMediaObjectDocuments(): Collection
    {
        return $this->mediaObjectDocuments;
    }

    public function addMediaObjectDocument(MediaObjectDocument $mediaObjectDocument): self
    {
        if (!$this->mediaObjectDocuments->contains($mediaObjectDocument)) {
            $this->mediaObjectDocuments[] = $mediaObjectDocument;
            $mediaObjectDocument->addPerson($this);
        }

        return $this;
    }

    public function removeMediaObjectDocument(MediaObjectDocument $mediaObjectDocument): self
    {
        if ($this->mediaObjectDocuments->removeElement($mediaObjectDocument)) {
            $mediaObjectDocument->removePerson($this);
        }

        return $this;
    }

}
