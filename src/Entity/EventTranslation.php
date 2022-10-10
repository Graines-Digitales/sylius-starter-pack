<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SeoTranslatableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * An event happening at a certain time and location, such as a concert, lecture, or festival. Ticketing information may be added via the \[\[offers\]\] property. Repeated events may be structured as separate Event objects.
 *
 * @see http://schema.org/Event Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/Event")
 * @ORM\Entity()
 * @ORM\Table(name="app_event_translation")
 */
class EventTranslation extends AbstractTranslation implements ResourceInterface
{
    use SeoTranslatableTrait;
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
    
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    public function getSlug()
    {
        return $this->slug;
    }

}
