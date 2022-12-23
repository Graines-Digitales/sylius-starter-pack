<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\AccommodationTranslation;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\AccommodationTrait;
use App\Repository\AccommodationRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * An accommodation is a place that can accommodate human beings, e.g. a hotel room, a camping pitch, or a meeting room. Many accommodations are for overnight stays, but this is not a mandatory requirement. For more specific types of accommodations not defined in schema.org, one can use additionalType with external vocabularies.
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/Accommodation Documentation on Schema.org
 *
 * @ORM\Table(name="app_accomodation")
 * @ApiResource(iri="http://schema.org/Accommodation")
 * @ORM\Entity(repositoryClass=AccommodationRepository::class)
 */
class Accommodation implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use AccommodationTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="accommodation")
     */
    private $rentals;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RentalPriceType", inversedBy="accommodations")
     */
    private $rentalPriceType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RealEstateAgent", inversedBy="accommodations")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $realEstateAgent;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $geo;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urbanTaxes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unionCharges;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $turnover;

    /**
     * @ORM\OneToMany(
     *  targetEntity="AccommodationDetail"
     *  , mappedBy="accommodation"
     *  , cascade= {"persist", "remove"}
     * )
     */
    private $details;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ourOpinion;

    /**
     * @ORM\ManyToMany(targetEntity=Person::class, inversedBy="accommodations")
     * @ORM\JoinTable(name="app_accommodations_teams")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="accommodation")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectImage::class)
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectImage::class)
     */
    private $secondaryImage;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="accommodations")
     * @ORM\JoinTable(name="app_accommodations_categories")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=MediaObjectDocument::class, mappedBy="accommodations")
     */
    private $mediaObjectDocuments;


    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->amenityFeatures = new ArrayCollection();
        $this->details = new ArrayCollection();
        $this->pdfs = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->rentals = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->owner = new ArrayCollection();
        $this->mediaObjectDocuments = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new AccommodationTranslation();
    }

    /**
     * Add Rental.
     *
     * @param \App\Entity\Event $rentals
     */
    public function addRental($rental)
    {
        $this->rentals[] = $rental;
    }

    /**
     * Remove $rental.
     *
     * @param \App\Entity\Event $rental
     */
    public function removeRental($rental)
    {
        $this->rentals->removeElement($rental);
    }

    /**
     * Get appointments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRentals()
    {
        return $this->rentals;
    }

    public function getRealEstateAgent(): ?RealEstateAgent
    {
        return $this->realEstateAgent;
    }

    public function setRealEstateAgent(?RealEstateAgent $realEstateAgent): self
    {
        $this->realEstateAgent = $realEstateAgent;

        return $this;
    }

    public function getRentalPriceType(): ?RentalPriceType
    {
        return $this->rentalPriceType;
    }

    public function setRentalPriceType(?RentalPriceType $rentalPriceType): self
    {
        $this->rentalPriceType = $rentalPriceType;

        return $this;
    }

    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * Set the value of geo.
     *
     * @param mixed geo
     *
     * @return self
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $isActive
     *
     * @return Beneficiary
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set the value of Rentals.
     *
     * @param mixed rentals
     *
     * @return self
     */
    public function setRentals($rentals)
    {
        $this->rentals = $rentals;

        return $this;
    }

    /**
     * Set the value of Urban Taxes.
     *
     * @param mixed urbanTaxes
     *
     * @return self
     */
    public function setUrbanTaxes($urbanTaxes)
    {
        $this->urbanTaxes = $urbanTaxes;

        return $this;
    }

    /**
     * Get the value of Urban Taxes.
     *
     * @return mixed
     */
    public function getUrbanTaxes()
    {
        return $this->urbanTaxes;
    }

    /**
     * Set the value of Union Charges.
     *
     * @param mixed unionCharges
     *
     * @return self
     */
    public function setUnionCharges($unionCharges)
    {
        $this->unionCharges = $unionCharges;

        return $this;
    }

    /**
     * Get the value of Union Charges.
     *
     * @return mixed
     */
    public function getUnionCharges()
    {
        return $this->unionCharges;
    }

    /**
     * Set the value of Turnover.
     *
     * @param mixed turnover
     *
     * @return self
     */
    public function setTurnover($turnover)
    {
        $this->turnover = $turnover;

        return $this;
    }

    /**
     * Get the value of Turnover.
     *
     * @return mixed
     */
    public function getTurnover()
    {
        return $this->turnover;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param \App\Entity\AccommodationDetail $detail
     */
    public function addDetail($detail)
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setAccommodation($this);
        }

        return $this;
    }

    /**
     * @param \App\Entity\AccommodationDetail $detail
     */
    public function removeDetail($detail)
    {
        if ($this->details->contains($detail)) {
            $this->details->removeElement($detail);
            // set the owning side to null (unless already changed)
            if ($detail->getAccommodation() === $this) {
                $detail->setAccommodation(null);
            }
        }
    }

    public function getOurOpinion(): ?string
    {
        return $this->ourOpinion;
    }

    public function setOurOpinion(?string $ourOpinion): void
    {
        $this->ourOpinion = $ourOpinion;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Person $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(Person $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(Person $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
            $owner->setAccommodation($this);
        }

        return $this;
    }

    public function removeOwner(Person $owner): self
    {
        if ($this->owner->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getAccommodation() === $this) {
                $owner->setAccommodation(null);
            }
        }

        return $this;
    }

    public function getPrimaryImage(): ?MediaObjectImage
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?MediaObjectImage $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

        return $this;
    }

    public function getSecondaryImage(): ?MediaObjectImage
    {
        return $this->secondaryImage;
    }

    public function setSecondaryImage(?MediaObjectImage $secondaryImage): self
    {
        $this->secondaryImage = $secondaryImage;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Category $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Category $tag): self
    {
        $this->tags->removeElement($tag);

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
            $mediaObjectDocument->addAccommodation($this);
        }

        return $this;
    }

    public function removeMediaObjectDocument(MediaObjectDocument $mediaObjectDocument): self
    {
        if ($this->mediaObjectDocuments->removeElement($mediaObjectDocument)) {
            $mediaObjectDocument->removeAccommodation($this);
        }

        return $this;
    }


}
