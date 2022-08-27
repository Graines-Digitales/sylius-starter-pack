<?php

namespace App\Entity;

use App\Entity\Traits\SeoTranslatableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Repository\WebPageRepository;
use App\Entity\Traits\CreativeWorkTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\LockableTrait;
use App\Entity\Traits\SeoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WebPageRepository::class)
 * @ORM\Table(name="app_web_page")
 */
class WebPage implements ResourceInterface, TranslatableInterface
{
    use SeoTrait;
    use LockableTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

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
        return new WebPageTranslation();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     * @Assert\NotBlank()
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="webPages")
     * @ORM\JoinTable(name="app_web_page_category")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=PropertyValue::class, mappedBy="webPage")
     */
    private $propertyValues;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObject::class, inversedBy="webPages")
     */
    private $secondaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObject::class, inversedBy="videoWebPages")
     */
    private $video;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $type;


    public function __toString()
    {
        if(null !== $this->getTranslation()->getHeadline()) {

            return $this->getTranslation()->getHeadline();
        }

        return  get_class($this) . ' - ID = ' . $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMetaTitle(): ?string
    {
        return $this->getTranslation()->getMetaTitle();
    }

    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    public function getHeadline(): ?string
    {
        return $this->getTranslation()->getHeadline();
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

    public function getComponents(): ?string
    {
//        return $this->getTranslation()->getComponent();
        return $this->getTranslation('fr_FR')->getComponents();
    }

    public function setComponents(string $components): self
    {
//        $this->getTranslation()->setComponent($component);
        $this->getTranslation('fr_FR')->setComponents($components);

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->getTranslation('fr_FR')->getSlug();
//        return $this->getTranslation()->getSlug();
    }

    public function getPrimaryImage(): ?MediaObject
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?MediaObject $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

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

    /**
     * @return Collection<int, PropertyValue>
     */
    public function getPropertyValues(): Collection
    {
        return $this->propertyValues;
    }

    public function addPropertyValue(PropertyValue $propertyValue): self
    {
        if (!$this->propertyValues->contains($propertyValue)) {
            $this->propertyValues[] = $propertyValue;
            $propertyValue->setWebPage($this);
        }

        return $this;
    }

    public function removePropertyValue(PropertyValue $propertyValue): self
    {
        if ($this->propertyValues->removeElement($propertyValue)) {
            // set the owning side to null (unless already changed)
            if ($propertyValue->getWebPage() === $this) {
                $propertyValue->setWebPage(null);
            }
        }

        return $this;
    }

    public function getSecondaryImage(): ?MediaObject
    {
        return $this->secondaryImage;
    }

    public function setSecondaryImage(?MediaObject $secondaryImage): self
    {
        $this->secondaryImage = $secondaryImage;

        return $this;
    }

    public function getVideo(): ?MediaObject
    {
        return $this->video;
    }

    public function setVideo(?MediaObject $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getType(): ?Category
    {
        return $this->type;
    }

    public function setType(?Category $type): self
    {
        $this->type = $type;

        return $this;
    }

}
