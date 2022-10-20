<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\SluggableNameTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_service")
 * @ORM\Entity(repositoryClass=HotelServiceRepository::class)
 */
class HotelService implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var bool
     *
     * @ORM\Column(name="withPicto", type="boolean", nullable=true)
     */
    private $withPicto;

    /**
     * @var bool
     *
     * @ORM\Column(name="slugPicto", type="string", nullable=true)
     */
    private $slugPicto;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $moreInfo;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="hotelServices", cascade={"persist"})
     * @ORM\JoinTable(name="app_hotel_services_categories")
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

    /**
     * Set withPicto.
     *
     * @param string $withPicto
     *
     * @return AmenityFeature
     */
    public function setWithPicto($withPicto)
    {
        $this->withPicto = $withPicto;

        return $this;
    }

    /**
     * Get withPicto.
     *
     * @return string
     */
    public function getWithPicto()
    {
        return $this->withPicto;
    }

    /**
     * Set slugPicto.
     *
     * @param string $slugPicto
     *
     * @return AmenityFeature
     */
    public function setSlugPicto($slugPicto)
    {
        $this->slugPicto = $slugPicto;

        return $this;
    }

    /**
     * Get slugPicto.
     *
     * @return string
     */
    public function getSlugPicto()
    {
        return $this->slugPicto;
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

    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }

    public function setMoreInfo(string $moreInfo): self
    {
        $this->moreInfo = $moreInfo;

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
