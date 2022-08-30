<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/? Documentation on Schema.org
 *
 * @ORM\Entity
 * @ORM\Table(name="app_rental")
 * @ApiResource(iri="http://schema.org/?")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Rental implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;


    /**
     * @ORM\ManyToOne(targetEntity="RentalType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * choice(maison, appart ... etc)
     */
    private $type;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set the value of Type.
     *
     * @param array type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of Type.
     *
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }
}
