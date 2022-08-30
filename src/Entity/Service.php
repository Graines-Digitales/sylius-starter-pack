<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
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
    use IdentifiableTrait;
    use SeoTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=LocalBusiness::class, inversedBy="services")
     * @ORM\JoinTable(name="app_services_localbusinesses")
     */
    private $localBusinesses;

    public function __construct()
    {
        $this->localBusinesses = new ArrayCollection();
    }

    public function __toString()
    {     
        return $this->getName();
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
    
}
