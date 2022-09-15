<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Sluggable\Util\Urlizer;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\MediaObjectTrait;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * A media object, such as an image, video, or audio object embedded in a web page or a downloadable dataset i.e. DataDownload. Note that a creative work may have many media objects associated with it on the same web page. For example, a page about a single song (MusicRecording) may have a music video (VideoObject), and a high and low bandwidth audio stream (2 AudioObject's).
 *
 * @see http://schema.org/MediaObject Documentation on Schema.org
 *
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ImageMediaObjectRepository::class)
 * @ORM\Table(name="app_image_media_object")
 * @Vich\Uploadable
 */
class ImageMediaObject implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ThingTrait;
    use CreativeWorkTrait;
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
     *    mapping="image_media_object"
     *  , fileNameProperty="slug"
     *  , size="contentSize"
     *  , mimeType="encodingFormat"
     *  , originalName="originalFilename"
     *  , dimensions="dimensions"
     * )
     * 
     * @Assert\NotBlank(groups={
     *     "media_object_image"
     * })
     * 
     * @Assert\File(
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif"
     *     }
     * )
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="messageAttachments", cascade={"persist"})
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="imageMediaObjects")
     * @ORM\JoinTable(name="app_image_media_objects_categories")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="primaryImage")
     */
    private $trips;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    public function __toString()
    {   
        return $this->getFilename();
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

    public function getFile(): ?File
    {
        return $this->file;
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

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

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
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->setPrimaryImage($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getPrimaryImage() === $this) {
                $trip->setPrimaryImage(null);
            }
        }

        return $this;
    }
    
}
