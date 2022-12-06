<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\LockableTrait;
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
 * An article, such as a news article or piece of investigative report. Newspapers and magazines have articles of many different types and this is intended to cover them all.\\n\\nSee also \[blog post\](http://blog.schema.org/2014/09/schemaorg-support-for-bibliographic\_2.html).
 *
 * @see https://schema.org/Article
 * @ApiResource(
 * iri="https://schema.org/Article",
 * itemOperations={
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
 *  collectionOperations={
 *    "post"={"security"="is_granted('ROLE_ADMIN')"}
 *  }
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="app_article")
 */
class Article implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use LockableTrait;
    use SeoTrait;
    use ImagesTrait;
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
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles", cascade={"persist"})
     * @ORM\JoinTable(name="app_articles_categories")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=PropertyValue::class, mappedBy="article")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $propertyValues;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectVideo::class, cascade={"persist"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alignPrimaryImage;


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

    public function getVideo(): ?MediaObjectVideo
    {
        return $this->video;
    }

    public function setVideo(?MediaObjectVideo $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getAlignPrimaryImage(): ?string
    {
        return $this->alignPrimaryImage;
    }

    public function setAlignPrimaryImage(?string $alignPrimaryImage): self
    {
        $this->alignPrimaryImage = $alignPrimaryImage;

        return $this;
    }

}
