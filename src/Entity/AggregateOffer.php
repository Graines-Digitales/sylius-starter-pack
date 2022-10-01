<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\OfferTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * When a single product is associated with multiple offers (for example, the same pair of shoes is offered by different merchants), then AggregateOffer can be used.
 *
 * @see http://schema.org/AggregateOffer Documentation on Schema.org
 *
 * @ORM\Entity
 * @ORM\Table(name="app_aggregate_offer")
 * @ApiResource(iri="http://schema.org/AggregateOffer")
 * @ORM\Entity(repositoryClass="App\Repository\AggregateOfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AggregateOffer implements ResourceInterface
{
    use IdentifiableTrait;
    use OfferTrait;
    use TimestampableEntity;

    /**
     * @var float|null the highest price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/highPrice")
     */
    private $highPrice;

    /**
     * @var float|null the lowest price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/lowPrice")
     */
    private $lowPrice;

    /**
     * @var int|null the number of offers for the product
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ApiProperty(iri="http://schema.org/offerCount")
     */
    private $offerCount;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="offers")
     */
    private $rooms;

    /**
     * @var float|null the addOn price of all offers available
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/addOn")
     */
    private $addOn;

    /**
     * @ORM\ManyToMany(targetEntity=Trip::class, mappedBy="offers")
     */
    private $trips;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param float|null $highPrice
     */
    public function setHighPrice($highPrice): void
    {
        $this->highPrice = $highPrice;
    }

    /**
     * @return float|null
     */
    public function getHighPrice()
    {
        return $this->highPrice;
    }

    /**
     * @param float|null $lowPrice
     */
    public function setLowPrice($lowPrice): void
    {
        $this->lowPrice = $lowPrice;
    }

    /**
     * @return float|null
     */
    public function getLowPrice()
    {
        return $this->lowPrice;
    }

    public function setOfferCount(?int $offerCount): void
    {
        $this->offerCount = $offerCount;
    }

    public function getOfferCount(): ?int
    {
        return $this->offerCount;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addOffer($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeOffer($this);
        }

        return $this;
    }

    // public function addAddOn(AggregateOffer $addOn): void
    // {
    //     $this->addOn[] = $addOn;
    // }

    // public function removeAddOn(AggregateOffer $addOn): void
    // {
    //     $this->addOn->removeElement($addOn);
    // }

    // public function getAddOn(): Collection
    // {
    //     return $this->addOn;
    // }

    /**
     * @param float|null $addOn
     */
    public function setAddOn($addOn): void
    {
        $this->addOn = $addOn;
    }

    /**
     * @return float|null
     */
    public function getAddOn()
    {
        return $this->addOn;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->addOffer($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            $trip->removeOffer($this);
        }

        return $this;
    }
}
