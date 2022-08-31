<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * @ApiResource()
 * @ORM\Table(name="app_accomodation_translation")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("accommodation")
     */
    private $informations;

    
    public function __construct()
    {
    
    }

    /**
     * Set the value of Informations.
     *
     * @param mixed informations
     *
     * @return self
     */
    public function setInformations($informations)
    {
        $this->informations = $informations;

        return $this;
    }

    /**
     * Get the value of Informations.
     *
     * @return mixed
     */
    public function getInformations()
    {
        return $this->informations;
    }

}
