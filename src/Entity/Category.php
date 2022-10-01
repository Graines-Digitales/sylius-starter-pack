<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\LockableTrait;
use App\Entity\Traits\SeoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @ApiResource()
 * @ApiResource(iri="http://schema.org/Category")
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="app_category")
 */
class Category implements ResourceInterface , TranslatableInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use LockableTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    
    
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
        $this->components = new ArrayCollection();
        $this->accommodations = new ArrayCollection();
        $this->hotelTypicalDays = new ArrayCollection();
        $this->hotelServices = new ArrayCollection();
        $this->hotelActivities = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->amenityFeatures = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

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
     * @ORM\ManyToMany(targetEntity=ImageMediaObject::class, mappedBy="tags")
     */
    private $imageMediaObjects;

    /**
     * @ORM\ManyToMany(targetEntity=VideoMediaObject::class, mappedBy="tags")
     */
    private $videoMediaObjects;

    /**
     * @ORM\ManyToMany(targetEntity=DocumentMediaObject::class, mappedBy="tags")
     */
    private $documentMediaObjects;

    /**
     * @ORM\ManyToMany(targetEntity=IconMediaObject::class, mappedBy="tags")
     */
    private $iconMediaObjects;

    /**
     * @ORM\ManyToOne(targetEntity=ImageMediaObject::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $primaryImage;

    /**
     * @ORM\ManyToMany(targetEntity=Accommodation::class, mappedBy="tags")
     */
    private $accommodations;

    /**
     * @ORM\ManyToMany(targetEntity=HotelTypicalDay::class, mappedBy="tags")
     */
    private $hotelTypicalDays;

    /**
     * @ORM\ManyToMany(targetEntity=HotelService::class, mappedBy="tags")
     */
    private $hotelServices;

    /**
     * @ORM\ManyToMany(targetEntity=HotelActivity::class, mappedBy="tags")
     */
    private $hotelActivities;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="tags")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=Component::class, mappedBy="tags")
     */
    private $components;

    /**
     * @ORM\ManyToMany(targetEntity=AmenityFeature::class, mappedBy="tags")
     */
    private $amenityFeatures;

    /**
     * @ORM\ManyToMany(targetEntity=Trip::class, mappedBy="tags")
     */
    private $trips;
    
    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new CategoryTranslation();
    }

    public function __toString()
    {
        if($this->getName()) {
            return $this->getName();
        }
        return 'test';
    }

    public function getSlug(): ?string
    {
        return $this->getTranslation()->getSlug();
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
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
    
    public function getPrimaryImage(): ?ImageMediaObject
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?ImageMediaObject $primaryImage): self
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
     * @return Collection<int, HotelTypicalDay>
     */
    public function getHotelTypicalDays(): Collection
    {
        return $this->hotelTypicalDays;
    }

    public function addHotelTypicalDay(HotelTypicalDay $hotelTypicalDay): self
    {
        if (!$this->hotelTypicalDays->contains($hotelTypicalDay)) {
            $this->hotelTypicalDays[] = $hotelTypicalDay;
            $hotelTypicalDay->addTag($this);
        }

        return $this;
    }

    public function removeHotelTypicalDay(HotelTypicalDay $hotelTypicalDay): self
    {
        if ($this->hotelTypicalDays->removeElement($hotelTypicalDay)) {
            $hotelTypicalDay->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelService>
     */
    public function getHotelServices(): Collection
    {
        return $this->hotelServices;
    }

    public function addHotelService(HotelService $hotelService): self
    {
        if (!$this->hotelServices->contains($hotelService)) {
            $this->hotelServices[] = $hotelService;
            $hotelService->addTag($this);
        }

        return $this;
    }

    public function removeHotelService(HotelService $hotelService): self
    {
        if ($this->hotelServices->removeElement($hotelService)) {
            $hotelService->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelActivity>
     */
    public function getHotelActivities(): Collection
    {
        return $this->hotelActivities;
    }

    public function addHotelActivity(HotelActivity $hotelActivity): self
    {
        if (!$this->hotelActivities->contains($hotelActivity)) {
            $this->hotelActivities[] = $hotelActivity;
            $hotelActivity->addTag($this);
        }

        return $this;
    }

    public function removeHotelActivity(HotelActivity $hotelActivity): self
    {
        if ($this->hotelActivities->removeElement($hotelActivity)) {
            $hotelActivity->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addTag($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeTag($this);
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
            $component->addTag($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            $component->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AmenityFeature>
     */
    public function getAmenityFeatures(): Collection
    {
        return $this->amenityFeatures;
    }

    public function addAmenityFeature(AmenityFeature $amenityFeature): self
    {
        if (!$this->amenityFeatures->contains($amenityFeature)) {
            $this->amenityFeatures[] = $amenityFeature;
            $amenityFeature->addTag($this);
        }

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
            $trip->setCategory($this);
        }

        return $this;
    }

    public function removeAmenityFeature(AmenityFeature $amenityFeature): self
    {
        if ($this->amenityFeatures->removeElement($amenityFeature)) {
            $amenityFeature->removeTag($this);
        }

        return $this;
    }
    
    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getCategory() === $this) {
                $trip->setCategory(null);
            }
        }

        return $this;
    }

}
