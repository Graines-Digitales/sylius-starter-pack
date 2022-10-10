<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\MediaObjectTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
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
 * @ApiResource(iri="http://schema.org/MediaObject")
 * @ORM\Table(name="app_media_object_video")
 * @ORM\Entity(repositoryClass=MediaObjectVideoRepository::class)
 */
class MediaObjectVideo implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ThingTrait;
    use MediaObjectTrait;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="mediaObjectVideos")
     * @ORM\JoinTable(name="app_media_object_videos_categories")
     */
    private $tags;


     /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->webPages = new ArrayCollection();
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
