<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Sluggable\Util\Urlizer;
use App\Entity\Traits\MediaObjectTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\Traits\SeoTrait;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * A media object, such as an image, video, or audio object embedded in a web page or a downloadable dataset i.e. DataDownload. Note that a creative work may have many media objects associated with it on the same web page. For example, a page about a single song (MusicRecording) may have a music video (VideoObject), and a high and low bandwidth audio stream (2 AudioObject's).
 *
 * @see http://schema.org/MediaObject Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/MediaObject")
 * @ORM\Table(name="app_icon_media_object")
 * @ORM\Entity(repositoryClass=IconMediaObjectRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class IconMediaObject implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ThingTrait;
    use MediaObjectTrait;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"filename"}, prefix="", updatable=true)
     * @ORM\Column(type="string", length=160, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @var File
     * @Vich\UploadableField(
     *    mapping="icon_media_object"
     *  , fileNameProperty="slug"
     *  , size="contentSize"
     *  , mimeType="encodingFormat"
     *  , originalName="originalFilename"
     *  , dimensions="dimensions"
     * )
     * 
     * @Assert\NotBlank(groups={
     *     "media_object_icon"
     * })
     * 
     * @Assert\File(
     *     mimeTypes = {
     *          "image/svg+xml"
     *     }
     * )
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="iconMediaObjects")
     * @ORM\JoinTable(name="app_icon_media_objects_categories")
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }
   
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($file) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getSlug()
    {
        if (!$this->slug && $this->getFile()) {
            
            return Urlizer::urlize(
                pathinfo($this->getFile()->getClientOriginalName(), PATHINFO_FILENAME)
            );
        } else if ($this->slug) {

            return $this->slug;    
        }

        return null;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
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
