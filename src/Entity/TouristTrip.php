<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * A tourist trip. A created itinerary of visits to one or more places of interest (\[\[TouristAttraction\]\]/\[\[TouristDestination\]\]) often linked by a similar theme, geographic area, or interest to a particular \[\[touristType\]\]. The \[UNWTO\](http://www2.unwto.org/) defines tourism trip as the Trip taken by visitors. (See examples below).
 *
 * @see https://schema.org/TouristTrip
 * 
 * @ApiResource(iri="https://schema.org/TouristTrip")
 * @ORM\Table(name="app_tourist_trip")
 * @ORM\Entity(repositoryClass="App\Repository\TouristTripRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TouristTrip
{
    use IdentifiableTrait;

    /**
     * @var string[]|null Attraction suitable for type(s) of tourist. eg. Children, visitors from a particular country, etc.
     *
     * @see https://schema.org/touristType
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/touristType')]
    private array $touristType = [];

    public function addTouristType(string $touristType): void
    {
        $this->touristType[] = $touristType;
    }

    public function removeTouristType(string $touristType): void
    {
        if (false !== $key = array_search($touristType, $this->touristType ?? [], true)) {
            unset($this->touristType[$key]);
        }
    }

    /**
     * @return string[]|null
     */
    public function getTouristType(): array
    {
        return $this->touristType;
    }
}
