<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/AmenityFeature Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/AmenityFeature")
 * @ORM\Entity(repositoryClass="App\Repository\AmenityFeatureTranslationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AmenityFeatureTranslation extends AbstractTranslation implements ResourceInterface
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
     * @ORM\ManyToMany(targetEntity="Accommodation", mappedBy="amenityFeatures")
     * @ORM\JoinTable(name="app_amenity_features_accommodations")
     */
    private $accommodations;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->accommodations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add accommodation.
     *
     * @param \App\Entity\Accommodation $accommodation
     *
     * @return Accommodation
     */
    public function addAccommodation($accommodation)
    {
        if ($this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->add($accommodation);
    }

    /**
     * Remove accommodation.
     *
     * @param \App\Entity\Accommodation $accommodation
     */
    public function removeAccommodation($accommodation)
    {
        if (!$this->accommodations->contains($accommodation)) {
            return;
        }

        $this->accommodations->removeElement($accommodation);
    }

    /**
     * Get accommodations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccommodations()
    {
        return $this->accommodations;
    }

    
}
