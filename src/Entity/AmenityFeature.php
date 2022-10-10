<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\HotelRoom;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\AmenityFeatureTranslation;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AmenityFeatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/AmenityFeature Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/AmenityFeature")
 * @ORM\Table(name="app_amenity_feature")
 * @ORM\Entity(repositoryClass=AmenityFeatureRepository::class)
 */
class AmenityFeature implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @ORM\ManyToMany(targetEntity="Accommodation", mappedBy="amenityFeatures")
     * @ORM\JoinTable(name="app_amenity_features_accommodations")
     */
    private $accommodations;

    /**
     * @ORM\ManyToMany(targetEntity=HotelRoom::class, mappedBy="amenityFeatures")
     */
    private $rooms;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade= {"persist", "remove"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="amenityFeatures", cascade= {"persist", "remove"})
     * @ORM\JoinTable(name="app_amenity_features_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $withPicto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slugPicto;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->accommodations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new AmenityFeatureTranslation();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->getTranslation()->getName();
    }

    /**
     * Add accommodation.
     *
     * @param \App\Entity\Accommodation $accommodation
     *
     * @return Accommodation
     */
    public function addAccommodation($accommodation)
    {
        if ($this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->add($accommodation);
    }

    /**
     * Remove accommodation.
     *
     * @param \App\Entity\Accommodation $accommodation
     */
    public function removeAccommodation($accommodation)
    {
        if (!$this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->removeElement($accommodation);
    }

    /**
     * Get accommodations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccommodations()
    {
        return $this->accommodations;
    }

    /**
     * @return Collection<int, HotelRoom>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(HotelRoom $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addAmenityFeature($this);
        }

        return $this;
    }

    public function removeRoom(HotelRoom $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeAmenityFeature($this);
        }

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

    public function getWithPicto(): ?bool
    {
        return $this->withPicto;
    }

    public function setWithPicto(?bool $withPicto): self
    {
        $this->withPicto = $withPicto;

        return $this;
    }

    public function getSlugPicto(): ?string
    {
        return $this->slugPicto;
    }

    public function setSlugPicto(?string $slugPicto): self
    {
        $this->slugPicto = $slugPicto;

        return $this;
    }



    
}
