<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_typical_day")
 * @ORM\Entity(repositoryClass="App\Repository\HotelTypicalDayRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class HotelTypicalDay implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;


    /**
     * @ORM\OneToMany(targetEntity="HotelTypicalDayElement", mappedBy="hotelTypicalDay", cascade= { "remove" })
     */
    private $elements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     */
    private $category;


    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="hotelTypicalDays")
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param \App\Entity\HotelTypicalDayElement $element
     */
    public function addElement($element)
    {
        if ($this->elements->contains($element)) {
            return;
        }

        $element->addHotelTypicalDay($this);
        $this->elements->add($element);
    }

    /**
     * @param \App\Entity\HotelTypicalDayElement $element
     */
    public function removeElement($element)
    {
        if (!$this->elements->contains($element)) {
            return;
        }

        $this->elements->removeElement($element);
        $element->removeHotelTypicalDay($this);
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
