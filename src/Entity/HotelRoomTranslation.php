<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * A hotel room is a single room in a hotel.
 *
 * See also the [dedicated document on the use of schema.org for marking up hotels and other forms of accommodations](/docs/hotels.html).
 *
 * @see http://schema.org/HotelRoom Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/HotelRoom")
 * @ORM\Table(name="app_hotel_room_translation")
 * @ORM\Entity(repositoryClass="App\Repository\HotelRoomTranslationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class HotelRoomTranslation extends AbstractTranslation implements ResourceInterface
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

    public function getSlug()
    {
        return $this->slug;
    }
}
