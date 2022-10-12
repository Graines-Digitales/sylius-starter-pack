<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SeoTranslatableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * A trip or journey. An itinerary of visits to one or more places.
 *
 * @see https://schema.org/Trip
 * 
 * @ApiResource(iri="https://schema.org/Trip")
 * @ORM\Table(name="app_trip_translation")
 * @ORM\Entity()
 */
class TripTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTranslatableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

   /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $components;

     /**
     * @Gedmo\Slug(fields={"headline"}, updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $structuredData = [];

    public function getComponents(): ?string
    {
        return $this->components;
    }

    public function setComponents(?string $components): self
    {
        $this->components = $components;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    public function getStructuredData(): ?array
    {
        return $this->structuredData;
    }

    public function setStructuredData(?array $structuredData): self
    {
        $this->structuredData = $structuredData;

        return $this;
    }
}
