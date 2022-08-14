<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\PlaceTrait;
use App\Entity\Traits\ThingTrait;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @see http://schema.org/Place Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Place")
 * @ORM\Table(name="app_place")
 */
class Place
{
   use ThingTrait;
   use PlaceTrait;

   /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    public function getId(): ?int
    {
        return $this->id;
    }

}
