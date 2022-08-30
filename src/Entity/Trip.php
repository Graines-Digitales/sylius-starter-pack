<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A trip or journey. An itinerary of visits to one or more places.
 *
 * @see https://schema.org/Trip
 * 
 * @ApiResource(iri="https://schema.org/Trip")
 * @ORM\Table(name="app_trip")
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Trip implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * The expected arrival time.
     *
     * @see https://schema.org/arrivalTime
     */
    #[ORM\Column(type: 'time', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/arrivalTime')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $arrivalTime = null;

    public function setArrivalTime(?\DateTimeInterface $arrivalTime): void
    {
        $this->arrivalTime = $arrivalTime;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }
}
