<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\LockableTrait;
use App\Repository\WebPageRepository;
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
 * A web page. Every web page is implicitly assumed to be declared to be of type WebPage, so the various properties about that webpage, such as `breadcrumb` may be used. We recommend explicit declaration if these properties are specified, but if they are found outside of an itemscope, they will be assumed to be about the page.
 *
 * @see https://schema.org/WebPage
 * @ApiResource(iri="https://schema.org/WebPage")
 * @ORM\Entity(repositoryClass=WebPageRepository::class)
 * @ORM\Table(name="app_web_page")
 */
class WebPage implements ResourceInterface, TranslatableInterface
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
        return new WebPageTranslation();
    }

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
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="webPages", cascade={"persist"})
     * @ORM\JoinTable(name="app_web_page_category")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=PropertyValue::class, mappedBy="webPage")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $propertyValues;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $type;

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
     * @ORM\ManyToOne(targetEntity=MediaObjectIcon::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alignPrimaryImage;

    public function __toString()
    {
        if(null !== $this->getTranslation()->getHeadline()) {

            return $this->getTranslation()->getHeadline();
        }

        return  get_class($this) . ' - ID = ' . $this->getId();
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
        return $this->getTranslation()->getComponents();
    }

    public function setComponents(string $components): self
    {
//        $this->getTranslation()->setComponent($component);
        $this->getTranslation()->setComponents($components);

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->getTranslation()->getSlug();
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


    public function getType(): ?Category
    {
        return $this->type;
    }

    public function setType(?Category $type): self
    {
        $this->type = $type;

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

    public function getIcon(): ?MediaObjectIcon
    {
        return $this->icon;
    }

    public function setIcon(?MediaObjectIcon $icon): self
    {
        $this->icon = $icon;

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
