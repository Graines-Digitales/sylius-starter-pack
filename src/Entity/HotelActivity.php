<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_activity")
 * @ORM\Entity(repositoryClass="App\Repository\HotelActivityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class HotelActivity implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;   
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var ImageMediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ImageMediaObject")
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     * @Assert\NotBlank(message="Select the main image hotel_activity")
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="hotelActivities")
     */
    private $tags;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new HotelActivityTranslation();
    }

    public function __toString()
    {
        return $this->getLabel().' => '.$this->getValue();
    }

    public function getLabel()
    {
        return $this->getTranslation()->getLabel();
    }

    public function getValue()
    {
        return $this->getTranslation()->getValue();
    }

    public function setPrimaryImage(?ImageMediaObject $primaryImage): void
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage(): ?ImageMediaObject
    {
        return $this->primaryImage;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
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
