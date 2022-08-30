<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource(iri="http://schema.org/Place")
 * @ORM\Table(name="app_accommodation_place_translation")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationPlaceTranslationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationPlaceTranslation extends AbstractTranslation implements ResourceInterface
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
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
