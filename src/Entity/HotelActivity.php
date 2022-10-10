<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\LockableTrait;
use App\Entity\HotelActivityTranslation;
use App\Entity\Traits\IdentifiableTrait;
use App\Repository\HotelActivityRepository;
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
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_activity")
 * @ORM\Entity(repositoryClass=HotelActivityRepository::class)
 */
class HotelActivity implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ImagesTrait;
    use TimestampableEntity;   
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }


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
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="hotelActivities", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="app_hotel_activities_categories")
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

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
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
