<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\LockableTrait;
use App\Entity\Traits\SeoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="app_category")
 */
class Category implements ResourceInterface , TranslatableInterface
{
    use SeoTrait;
    use LockableTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    use TimestampableEntity;
    
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->articles = new ArrayCollection();
        $this->webPages = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->specialAnnouncements = new ArrayCollection();
        $this->searchActions = new ArrayCollection();
        $this->localBusinesses = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->mediaObjects = new ArrayCollection();
        $this->components = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="tags")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=WebPage::class, mappedBy="tags")
     */
    private $webPages;

    /**
     * @ORM\ManyToMany(targetEntity=SpecialAnnouncement::class, mappedBy="tags")
     */
    private $specialAnnouncements;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="category")
     */
    private $organizations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=SearchAction::class, mappedBy="tags")
     * 
     */
    private $searchActions;

    /**
     * @ORM\ManyToMany(targetEntity=LocalBusiness::class, inversedBy="categories")
     * @ORM\JoinTable(name="app_category_localbusiness")
     */
    private $localBusinesses;

    /**
     * @ORM\OneToMany(targetEntity=MediaObject::class, mappedBy="category")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=MediaObject::class, mappedBy="tags")
     */
    private $mediaObjects;
    
        /**
     * @ORM\ManyToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $primaryImage;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="category")
     */
    private $components;
    
    public function __toString()
    {
        return $this->getName();
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(string $name): self
    {
        $this->getTranslation()->setName($name);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): self
    {
        $this->getTranslation()->setDescription($description);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new CategoryTranslation();
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
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

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
            $webPage->setCategory($this);
        }

        return $this;
    }

    public function removeWebPage(WebPage $webPage): self
    {
        if ($this->webPages->removeElement($webPage)) {
            // set the owning side to null (unless already changed)
            if ($webPage->getCategory() === $this) {
                $webPage->setCategory(null);
            }
        }

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
            $organization->setCategory($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
        if ($this->organizations->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getCategory() === $this) {
                $organization->setCategory(null);
            }
        }

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, SearchAction>
     */
    public function getSearchActions(): Collection
    {
        return $this->searchActions;
    }

    public function addSearchAction(SearchAction $searchAction): self
    {
        if (!$this->searchActions->contains($searchAction)) {
            $this->searchActions[] = $searchAction;
            $searchAction->addTag($this);
        }

        return $this;
    }

    public function removeSearchAction(SearchAction $searchAction): self
    {
        if ($this->searchActions->removeElement($searchAction)) {
            $searchAction->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, LocalBusiness>
     */
    public function getLocalBusinesses(): Collection
    {
        return $this->localBusinesses;
    }

    public function addLocalBusiness(LocalBusiness $localBusiness): self
    {
        if (!$this->localBusinesses->contains($localBusiness)) {
            $this->localBusinesses[] = $localBusiness;
        }

        return $this;
    }

    public function removeLocalBusiness(LocalBusiness $localBusiness): self
    {
        $this->localBusinesses->removeElement($localBusiness);

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getMediaObjects(): Collection
    {
        return $this->mediaObjects;
    }

    public function addMediaObject(MediaObject $mediaObject): self
    {
        if (!$this->mediaObjects->contains($mediaObject)) {
            $this->mediaObjects[] = $mediaObject;
            $mediaObject->addTag($this);
        }

        return $this;
    }

    public function removeMediaObject(MediaObject $mediaObject): self
    {
        if ($this->mediaObjects->removeElement($mediaObject)) {
            $mediaObject->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Component>
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setCategory($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getCategory() === $this) {
                $component->setCategory(null);
            }
        }

        return $this;
    }

}
