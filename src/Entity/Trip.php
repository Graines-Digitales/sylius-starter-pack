<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\AggregateOffer;
use App\Entity\Traits\SeoTrait;
use App\Entity\TripTranslation;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\LockableTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\TranslatableInterface;



/**
 * A trip or journey. An itinerary of visits to one or more places.
 *
 * @see https://schema.org/Trip
 * 
 * @ApiResource(iri="https://schema.org/Trip")
 * @ORM\Table(name="app_trip")
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip  implements ResourceInterface, TranslatableInterface   
{
    use IdentifiableTrait;
    use LockableTrait;
    use SeoTrait;
    use ImagesTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->tags = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new TripTranslation();
    }

    /**
     * The expected arrival time.
     *
     * @see https://schema.org/arrivalTime
     * 
     * @ORM\Column(name="departure_time", type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $arrivalTime = null;

    /**
     * The expected departure time.
     *
     * @see https://schema.org/departureTime
     * 
     * @ORM\Column(name="arrival_time", type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $departureTime = null;


    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="trips")
     * @ORM\JoinTable(name="app_amenity_trips_categories")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=AggregateOffer::class, inversedBy="trips", cascade={"persist"})
     * @ORM\JoinTable(name="app_trips_offers")
     * 
     * @Assert\Count(
     *      min = 1,
     *      max = 5,
     *      minMessage = "You must specify at least one offer",
     *      maxMessage = "You cannot specify more than {{ limit }} offers"
     * )
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $offers;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alignPrimaryImage;

    public function __toString()
    {
        return $this->getTranslation()->getAlternativeHeadline();
    }

    public function getHeadline(): ?string
    {
        return $this->getTranslation()->getHeadline();
    }

    public function getMetaTitle(): ?string
    {
        return $this->getTranslation()->getMetaTitle();
    }

    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    public function setHeadline(string $headline): self
    {
        $this->getTranslation()->setHeadline($headline);

        return $this;
    }

    public function getAlternativeHeadline(): ?string
    {
        return $this->getTranslation()->getAlternativeHeadline();
    }
    
    public function setComponents(string $components): self
    {
        $this->getTranslation()->setComponents($components);

        return $this;
    }

    public function setArrivalTime(?\DateTimeInterface $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setDepartureTime(?\DateTimeInterface $departureTime): void
    {
        $this->departureTime = $departureTime;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
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
     * @return Collection<int, AggregateOffer>
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAlignPrimaryImage(): ?string
    {
        return $this->alignPrimaryImage;
    }

    public function setAlignPrimaryImage(?string $alignPrimaryImage): self
    {
        $this->alignPrimaryImage = $alignPrimaryImage;

        return $this;
    }
    
}
