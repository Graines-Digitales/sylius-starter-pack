<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Sluggable\Util\Urlizer;
use App\Entity\Traits\MediaObjectTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
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
 * @ORM\Table(name="app_document_media_object")
 * @ORM\Entity(repositoryClass=DocumentMediaObjectRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class DocumentMediaObject implements ResourceInterface
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
     *    mapping="document_media_object"
     *  , fileNameProperty="slug"
     *  , size="contentSize"
     *  , mimeType="encodingFormat"
     *  , originalName="originalFilename"
     *  , dimensions="dimensions"
     * )
     * 
     * @Assert\NotBlank(groups={
     *     "media_object_document"
     * })
     * 
     * @Assert\File(
     *     mimeTypes = {
     *          "application/pdf"
     *     }
     * )
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentMediaObjectType")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Person::class, inversedBy="documentMediaObjects")
     * @ORM\JoinTable(name="app_document_media_objects_persons")
     */
    private $persons;

    /**
     * @ORM\ManyToMany(targetEntity=Accommodation::class, inversedBy="documentMediaObjects")
     * @ORM\JoinTable(name="app_document_media_objects_accommodations")
     */
    private $accommodations;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="documentMediaObjects")
     * @ORM\JoinTable(name="app_document_media_objects_categories")
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->accommodations = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
    
    public function getType(): ?DocumentMediaObjectType
    {
        return $this->type;
    }

    public function setType(?DocumentMediaObjectType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        $this->persons->removeElement($person);

        return $this;
    }

    /**
     * @return Collection<int, Accommodation>
     */
    public function getAccommodations(): Collection
    {
        return $this->accommodations;
    }

    public function addAccommodation(Accommodation $accommodation): self
    {
        if (!$this->accommodations->contains($accommodation)) {
            $this->accommodations[] = $accommodation;
        }

        return $this;
    }

    public function removeAccommodation(Accommodation $accommodation): self
    {
        $this->accommodations->removeElement($accommodation);

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
}
