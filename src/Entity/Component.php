<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\ComponentTranslation;
use App\Entity\Traits\LockableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\ComponentRepository;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @ApiResource(
 * itemOperations={
 *    "get",
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
 *  collectionOperations={
 *    "get",
 *    "post"={"security"="is_granted('ROLE_ADMIN')"}
 *  }
 * )
 * @ORM\Entity(repositoryClass=ComponentRepository::class)
 * @ORM\Table(name="app_component")
 */
class Component implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use LockableTrait;
    use SeoTrait;
    use ThingTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="components")
     * @ORM\JoinTable(name="app_components_categories")
     */
    private $tags;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new ComponentTranslation();
    }

    public function __toString()
    {
        return $this->getName();
    }

     /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
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
