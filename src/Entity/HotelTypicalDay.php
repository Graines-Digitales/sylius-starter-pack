<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_typical_day")
 * @ORM\Entity(repositoryClass=HotelTypicalDayRepository::class)
 */
class HotelTypicalDay implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @ORM\OneToMany(targetEntity="HotelTypicalDayElement", mappedBy="hotelTypicalDay", cascade={"persist", "remove"})
     */
    private $elements;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist", "remove"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="hotelTypicalDays")
     * @ORM\JoinTable(name="app_hotel_typical_days_categories")
     */
    private $tags;

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new HotelTypicalDayElementTranslation();
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->elements = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTranslation()->getName();
    }

    public function getSlug()
    {
        return $this->getTranslation()->getSlug();
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

        $element->setHotelTypicalDay($this);
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
