<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\RoomTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * A hotel room is a single room in a hotel.
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/HotelRoom Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/HotelRoom")
 * @ORM\Table(name="app_hotel_room")
 * @ORM\Entity(repositoryClass=HotelRoomRepository::class)
 */
class HotelRoom implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use RoomTrait;
    use ImagesTrait;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=AmenityFeature::class, inversedBy="rooms", cascade= {"persist", "remove"})
     * @ORM\JoinTable(name="app_rooms_amenity_features")
     */
    private $amenityFeatures;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="rooms")
     * @ORM\JoinTable(name="app_rooms_events")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=AggregateOffer::class, inversedBy="rooms", cascade={"persist"})
     * @ORM\JoinTable(name="app_rooms_offers")
     */
    private $offers;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->offers = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->amenityFeatures = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new HotelRoomTranslation();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->getTranslation()->getName();
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
}