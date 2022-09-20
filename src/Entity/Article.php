<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\LockableTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="app_article")
 */
class Article implements ResourceInterface, TranslatableInterface
{
    use SeoTrait;
    use LockableTrait;
    use IdentifiableTrait;
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
        return new ArticleTranslation();
    }

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datePublished;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $lastReview;

    /**
     * @ORM\ManyToOne(targetEntity=ImageMediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=ImageMediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $secondaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinTable(name="app_article_category")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=PropertyValue::class, mappedBy="article")
     */
    private $propertyValues;

    /**
     * @ORM\ManyToOne(targetEntity=VideoMediaObject::class, inversedBy="articles")
     */
    private $video;


    public function __toString()
    {
        return $this->getTranslation()->getHeadline();
    }

    public function getHeadline(): ?string
    {
        return $this->getTranslation()->getHeadline();
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function getComponents(): ?string
    {
        return $this->getTranslation()->getComponents();
    }

    public function setComponents(string $components): self
    {
        $this->getTranslation()->setComponents($components);

        return $this;
    }
    
    public function getArticleBody(): ?string
    {
        return $this->getTranslation()->getArticleBody();
    }

    public function setArticleBody(?string $articleBody): self
    {
        $this->getTranslation()->setArticleBody($articleBody);

        return $this;
    }

    public function getArticleResume(): ?string
    {
        return $this->getTranslation()->getArticleResume();
    }

    public function setArticleResume(?string $articleResume): self
    {
        $this->getTranslation()->setArticleResume($articleResume);

        return $this;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(?\DateTimeInterface $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getLastReview(): ?\DateTimeInterface
    {
        return $this->lastReview;
    }

    public function setLastReview(?\DateTimeInterface $lastReview): self
    {
        $this->lastReview = $lastReview;

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

    public function getPrimaryImage(): ?ImageMediaObject
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?ImageMediaObject $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

        return $this;
    }

    public function getSecondaryImage(): ?ImageMediaObject
    {
        return $this->secondaryImage;
    }

    public function setSecondaryImage(?ImageMediaObject $secondaryImage): self
    {
        $this->secondaryImage = $secondaryImage;

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

    public function getVideo(): ?VideoMediaObject
    {
        return $this->video;
    }

    public function setVideo(?VideoMediaObject $video): self
    {
        $this->video = $video;

        return $this;
    }

}
