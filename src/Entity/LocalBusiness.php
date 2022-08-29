<?php

namespace App\Entity;

use App\Entity\Product\Product;
use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\PlaceTrait;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\LocalBusinessRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LocalBusinessRepository::class)
 * @ORM\Table(name="app_local_business")
 */
class LocalBusiness implements ResourceInterface
{
    use SeoTrait;
    use ThingTrait;
    use PlaceTrait;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="localBusinesses", cascade={"persist"}))
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $organization;

    /**
     * @ORM\OneToMany(targetEntity=OpeningHoursSpecification::class, mappedBy="localBusiness", cascade={"persist"})
     */
    private $openingHours;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, mappedBy="localBusinesses")
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="localBusinesses")
     * @ORM\JoinTable(name="app_localbusiness_category")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gmap;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="localBusinesses")
     */
    private $products;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="localBusiness")
    //  */
    // private $message;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="localBusiness")
     */
    private $messages;

    public function __construct()
    {
        $this->openingHours = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {     
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
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
     * @return Collection<int, OpeningHoursSpecification>
     */
    public function getOpeningHours(): Collection
    {
        return $this->openingHours;
    }

    public function addOpeningHour(OpeningHoursSpecification $openingHour): self
    {
        if (!$this->openingHours->contains($openingHour)) {
            $this->openingHours[] = $openingHour;
            $openingHour->setLocalBusiness($this);
        }

        return $this;
    }

    public function removeOpeningHour(OpeningHoursSpecification $openingHour): self
    {
        if ($this->openingHours->removeElement($openingHour)) {
            // set the owning side to null (unless already changed)
            if ($openingHour->getLocalBusiness() === $this) {
                $openingHour->setLocalBusiness(null);
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
            $service->addLocalbusiness($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            $service->removeLocalBusiness($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addLocalBusiness($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeLocalBusiness($this);
        }

        return $this;
    }

    public function getGmap(): ?string
    {
        return $this->gmap;
    }

    public function setGmap(string $gmap): self
    {
        $this->gmap = $gmap;

        return $this;
    }

    public function getProducts(): ?Product
    {
        return $this->products;
    }

    public function setProducts(?Product $products): self
    {
        $this->products = $products;

        return $this;
    }

    // public function getMessage(): ?Message
    // {
    //     return $this->message;
    // }

    // public function setMessage(?Message $message): self
    // {
    //     $this->message = $message;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Message>
    //  */
    // public function getMessages(): Collection
    // {
    //     return $this->messages;
    // }

    // public function addMessage(Message $message): self
    // {
    //     if (!$this->messages->contains($message)) {
    //         $this->messages[] = $message;
    //         $message->setLocalBusiness($this);
    //     }

    //     return $this;
    // }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getLocalBusiness() === $this) {
                $message->setLocalBusiness(null);
            }
        }

        return $this;
    }
}
