<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_accommodation_nature_translation")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationNatureTranslationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationNatureTranslation extends AbstractTranslation implements ResourceInterface
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
