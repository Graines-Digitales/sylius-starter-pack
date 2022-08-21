<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Sluggable\Util\Urlizer;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\CreativeWorkTrait;
use App\Repository\MediaObjectRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * A media object, such as an image, video, or audio object embedded in a web page or a downloadable dataset i.e. DataDownload. Note that a creative work may have many media objects associated with it on the same web page. For example, a page about a single song (MusicRecording) may have a music video (VideoObject), and a high and low bandwidth audio stream (2 AudioObject's).
 *
 * @see http://schema.org/MediaObject Documentation on Schema.org
 *
 * @ORM\Entity(repositoryClass=MediaObjectRepository::class)
 * @ORM\Table(name="app_media_object")
 * @Vich\Uploadable
 */
class MediaObject implements ResourceInterface
{
    use SeoTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;
   
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups("identifier")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"filename"}, prefix="", updatable=true)
     * @ORM\Column(type="string", length=160, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @var int|null file size in (mega/kilo) bytes
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ApiProperty(iri="http://schema.org/contentSize")
     */
    private $contentSize;

    /**
     * @var string|null mp3, mpeg4, etc
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/encodingFormat")
     */
    private $encodingFormat;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ Assert\NotNull
     */
    private $originalFilename;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=true)
     * @ Assert\NotNull
     */
    private $dimensions;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "1G",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf"
     *      },
     *     mimeTypesMessage = "Formats autorisés : pdf, png, jpeg, jpg, gif"
     * )
     * @Vich\UploadableField(
     *    mapping="default_media_object"
     *  , fileNameProperty="slug"
     *  , size="contentSize"
     *  , mimeType="encodingFormat"
     *  , originalName="originalFilename"
     *  , dimensions="dimensions"
     * )
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tmpFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActived = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="video")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="iconMedias")
     */
    private $organization;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="iconMedia")
     */
    private $organizations;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="icon")
     */
    private $services;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="messageAttachments", cascade={"persist"})
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity=WebPage::class, mappedBy="secondaryImage")
     */
    private $webPages;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="secondaryImage")
     */
    private $secondaryImageArticles;

    /**
     * @ORM\OneToMany(targetEntity=WebPage::class, mappedBy="video")
     */
    private $videoWebPages;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="mediaObjects")
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->webPages = new ArrayCollection();
        $this->secondaryImageArticles = new ArrayCollection();
        $this->videoWebPages = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {   
        if(!empty($this->getOriginalFilename())) {
            
            return $this->getOriginalFilename();
        }

        return $this->getFilename();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setContentSize($contentSize): void
    {
        $this->contentSize = $contentSize;
    }

    public function getContentSize()
    {
        return $this->contentSize;
    }

    public function setEncodingFormat(?string $encodingFormat): void
    {
        $this->encodingFormat = $encodingFormat;
    }

    public function getEncodingFormat(): ?string
    {
        return $this->encodingFormat;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
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

    /**
     * Set the value of Original Name.
     *
     * @param string originalFilename
     *
     * @return self
     */
    public function setOriginalFilename(?string $originalFilename): void
    {
        $this->originalFilename = $originalFilename;
    }

    /**
     * Get the value of Original Name.
     *
     * @return string
     */
    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    /**
     * Set the value of Dimensions.
     *
     * @param string dimensions
     *
     * @return self
     */
    public function setDimensions(?array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    /**
     * Get the value of Dimensions.
     *
     * @return string
     */
    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }
  

    /*
    * Set tmpFile
    * @return Image
    */
    public function setTmpFile($tmpFile)
    {
        $this->tmpFile = $tmpFile;

        return $this;
    }

    /*
    * Get tmpFile
    * @return string
    */
    public function getTmpFile()
    {
        return $this->tmpFile;
    }

    /**
     * Set the value of Caption.
     *
     * @param string|null caption
     *
     * @return self
     */
    public function setCaption(?string $caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get the value of Caption.
     *
     * @return string|null
     */
    public function getCaption()
    {
        return $this->caption;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getIsActived(): ?bool
    {
        return $this->isActived;
    }

    public function setIsActived(bool $isActived): self
    {
        $this->isActived = $isActived;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
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

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setVideo($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getVideo() === $this) {
                $article->setVideo(null);
            }
        }

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
            $organization->setIconMedia($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
        if ($this->organizations->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getIconMedia() === $this) {
                $organization->setIconMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setIcon($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getIcon() === $this) {
                $service->setIcon(null);
            }
        }

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

    /**
     * @return Collection<int, WebPage>
     */
    public function getWebPages(): Collection
    {
        return $this->webPages;
    }

    public function addWebPage(WebPage $webPage): self
    {
        if (!$this->webPages->contains($webPage)) {
            $this->webPages[] = $webPage;
            $webPage->setSecondaryImage($this);
        }

        return $this;
    }

    public function removeWebPage(WebPage $webPage): self
    {
        if ($this->webPages->removeElement($webPage)) {
            // set the owning side to null (unless already changed)
            if ($webPage->getSecondaryImage() === $this) {
                $webPage->setSecondaryImage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getSecondaryImageArticles(): Collection
    {
        return $this->secondaryImageArticles;
    }

    public function addSecondaryImageArticle(Article $secondaryImageArticle): self
    {
        if (!$this->secondaryImageArticles->contains($secondaryImageArticle)) {
            $this->secondaryImageArticles[] = $secondaryImageArticle;
            $secondaryImageArticle->setSecondaryImage($this);
        }

        return $this;
    }

    public function removeSecondaryImageArticle(Article $secondaryImageArticle): self
    {
        if ($this->secondaryImageArticles->removeElement($secondaryImageArticle)) {
            // set the owning side to null (unless already changed)
            if ($secondaryImageArticle->getSecondaryImage() === $this) {
                $secondaryImageArticle->setSecondaryImage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WebPage>
     */
    public function getVideoWebPages(): Collection
    {
        return $this->videoWebPages;
    }

    public function addVideoWebPage(WebPage $videoWebPage): self
    {
        if (!$this->videoWebPages->contains($videoWebPage)) {
            $this->videoWebPages[] = $videoWebPage;
            $videoWebPage->setVideo($this);
        }

        return $this;
    }

    public function removeVideoWebPage(WebPage $videoWebPage): self
    {
        if ($this->videoWebPages->removeElement($videoWebPage)) {
            // set the owning side to null (unless already changed)
            if ($videoWebPage->getVideo() === $this) {
                $videoWebPage->setVideo(null);
            }
        }

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
