<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\PlaceTrait;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @see http://schema.org/Place Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Place")
 * @ORM\Table(name="app_place")
 */
class Place implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use PlaceTrait;
}
