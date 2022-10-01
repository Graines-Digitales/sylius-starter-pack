<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\AmenityFeatureTranslation;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\ThingTrait;
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
 * @ORM\Entity(repositoryClass="App\Repository\AmenityFeatureRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="amenityFeatures")
     */
    private $rooms;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="amenityFeatures")
     * @ORM\JoinTable(name="app_amenity_features_tags")
     */
    private $tags;

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
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addAmenityFeature($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
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

    
}
