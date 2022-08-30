<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/? Documentation on Schema.org
 *
 * @ORM\Entity
 * @ORM\Table(name="app_rental_price_type")
 * @ApiResource(iri="http://schema.org/?")
 * @ORM\HasLifecycleCallbacks()
 */
class RentalPriceType implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;


    /**
     * @Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Accommodation", mappedBy="rentalPriceType")
     */
    private $accommodations;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->accommodations = new ArrayCollection();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|Accommodation[]
     */
    public function getAccommodations(): Collection
    {
        return $this->accommodations;
    }

    public function addAccommodation(Accommodation $accommodation): self
    {
        if (!$this->accommodations->contains($accommodation)) {
            $this->accommodations[] = $accommodation;
            $accommodation->setRentalPriceType($this);
        }

        return $this;
    }

    public function removeAccommodation(Accommodation $accommodation): self
    {
        if ($this->accommodations->contains($accommodation)) {
            $this->accommodations->removeElement($accommodation);
            // set the owning side to null (unless already changed)
            if ($accommodation->getRentalPriceType() === $this) {
                $accommodation->setRentalPriceType(null);
            }
        }

        return $this;
    }
}
