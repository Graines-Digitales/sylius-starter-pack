<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * A room is a distinguishable space within a structure, usually separated from other spaces by interior walls. (Source: Wikipedia, the free encyclopedia, see <http://en.wikipedia.org/wiki/Room>).
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/Room Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Room")
 * @ORM\Table(name="app_room")
 * @ORM\HasLifecycleCallbacks()
 */
class Room implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string|null an alias for the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/alternateName")
     */
    private $alternateName;

    /**
     * @var ImageMediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ImageMediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Assert\NotBlank(message="Select the main image room")
     */
    private $primaryImage;

    /**
     * @var ImageMediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ImageMediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $secondaryImage;

    /**
     * @ORM\Column(name="maximumOccupants", type="smallint", nullable=true)
     *
     * @ Assert\NotBlank(message="Enter the number of occupants")
     */
    private $maximumOccupants;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", length=6, nullable=true)
     *
     * @Assert\NotBlank(message="Enter the price of the property")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $labelBgTransparent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfRooms;

    /**
     * @ORM\ManyToMany(targetEntity=AggregateOffer::class, inversedBy="rooms", cascade={"persist"})
     * @ORM\JoinTable(name="app_rooms_offers")
     */
    private $offers;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="rooms")
     * @ORM\JoinTable(name="app_rooms_events")
     */
    private $events;

    /**
     * @ORM\Column(type="smallint")
     */
    private $minimumOccupants;

    /**
     * @ORM\ManyToMany(targetEntity=AmenityFeature::class, inversedBy="rooms")
     * @ORM\JoinTable(name="app_rooms_amenity_features")
     */
    private $amenityFeatures;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->amenityFeatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setAlternateName(?string $alternateName): void
    {
        $this->alternateName = $alternateName;
    }

    public function getAlternateName(): ?string
    {
        return $this->alternateName;
    }

    public function setPrimaryImage(?ImageMediaObject $primaryImage): void
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage(): ?ImageMediaObject
    {
        return $this->primaryImage;
    }

    public function setSecondaryImage(?ImageMediaObject $secondaryImage): void
    {
        $this->secondaryImage = $secondaryImage;
    }

    public function getSecondaryImage(): ?ImageMediaObject
    {
        return $this->secondaryImage;
    }

    /**
     * Get the value of MaximumOccupants.
     *
     * @return int
     */
    public function getMaximumOccupants()
    {
        return $this->maximumOccupants;
    }

    /**
     * Set the value of MaximumOccupants.
     *
     * @param int MaximumOccupants
     *
     * @return self
     */
    public function setMaximumOccupants($maximumOccupants)
    {
        $this->maximumOccupants = $maximumOccupants;

        return $this;
    }

    /**
     * Set the value of Price.
     *
     * @param float price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of Price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getLabelBgTransparent(): ?bool
    {
        return $this->labelBgTransparent;
    }

    public function setLabelBgTransparent(bool $labelBgTransparent): self
    {
        $this->labelBgTransparent = $labelBgTransparent;

        return $this;
    }

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(?int $numberOfRooms): self
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    /**
     * @return Collection|AggregateOffer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(AggregateOffer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
        }

        return $this;
    }

    public function removeOffer(AggregateOffer $offer): self
    {
        $this->offers->removeElement($offer);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setRoom($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRoom() === $this) {
                $event->setRoom(null);
            }
        }

        return $this;
    }

    public function getMinimumOccupants(): ?int
    {
        return $this->minimumOccupants;
    }

    public function setMinimumOccupants(int $minimumOccupants): self
    {
        $this->minimumOccupants = $minimumOccupants;

        return $this;
    }

    /**
     * @return Collection<int, AmenityFeature>
     */
    public function getAmenityFeatures(): Collection
    {
        return $this->amenityFeatures;
    }

    public function addAmenityFeature(AmenityFeature $amenityFeature): self
    {
        if (!$this->amenityFeatures->contains($amenityFeature)) {
            $this->amenityFeatures[] = $amenityFeature;
        }

        return $this;
    }

    public function removeAmenityFeature(AmenityFeature $amenityFeature): self
    {
        $this->amenityFeatures->removeElement($amenityFeature);

        return $this;
    }

}
