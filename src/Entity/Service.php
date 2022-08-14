<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Repository\ServiceRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\SeoTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;



/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(name="app_service")
 */
class Service implements ResourceInterface
{
    use SeoTrait;
    use ThingTrait;
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
     * @ORM\ManyToMany(targetEntity=LocalBusiness::class, inversedBy="services")
     * @ORM\JoinTable(name="app_service_localbusiness")
     */
    private $localBusinesses;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObject::class, inversedBy="services")
     */
    private $icon;

    public function __construct()
    {
        $this->localBusinesses = new ArrayCollection();
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
            $localBusiness->addService($this);
        }

        return $this;
    }

    public function removeLocalBusiness(LocalBusiness $localBusiness): self
    {
        if ($this->localBusinesses->removeElement($localBusiness)) {
            $localBusiness->removeService($this);
        }

        return $this;
    }

    public function getIcon(): ?MediaObject
    {
        return $this->icon;
    }

    public function setIcon(?MediaObject $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
    
}
