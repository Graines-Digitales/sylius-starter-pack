<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * A reservation for air travel.\\n\\nNote: This type is for information about actual reservations, e.g. in confirmation emails or HTML pages with individual confirmations of reservations. For offers of tickets, use \[\[Offer\]\].
 *
 * @see https://schema.org/FlightReservation
 * 
 * @ApiResource(iri="https://schema.org/FlightReservation")
 * @ORM\Table(name="app_flight_reservation")
 * @ORM\Entity(repositoryClass="App\Repository\FlightReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class FlightReservation
{
    use IdentifiableTrait;

    /**
     * The passenger's sequence number as assigned by the airline.
     *
     * @see https://schema.org/passengerSequenceNumber
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/passengerSequenceNumber')]
    private ?string $passengerSequenceNumber = null;

    /**
     * The airline-specific indicator of boarding order / preference.
     *
     * @see https://schema.org/boardingGroup
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/boardingGroup')]
    private ?string $boardingGroup = null;

    /**
     * The priority status assigned to a passenger for security or boarding (e.g. FastTrack or Priority).
     *
     * @see https://schema.org/passengerPriorityStatus
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/passengerPriorityStatus')]
    private ?string $passengerPriorityStatus = null;

    /**
     * The type of security screening the passenger is subject to.
     *
     * @see https://schema.org/securityScreening
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/securityScreening')]
    private ?string $securityScreening = null;

    public function setPassengerSequenceNumber(?string $passengerSequenceNumber): void
    {
        $this->passengerSequenceNumber = $passengerSequenceNumber;
    }

    public function getPassengerSequenceNumber(): ?string
    {
        return $this->passengerSequenceNumber;
    }

    public function setBoardingGroup(?string $boardingGroup): void
    {
        $this->boardingGroup = $boardingGroup;
    }

    public function getBoardingGroup(): ?string
    {
        return $this->boardingGroup;
    }

    public function setPassengerPriorityStatus(?string $passengerPriorityStatus): void
    {
        $this->passengerPriorityStatus = $passengerPriorityStatus;
    }

    public function getPassengerPriorityStatus(): ?string
    {
        return $this->passengerPriorityStatus;
    }

    public function setSecurityScreening(?string $securityScreening): void
    {
        $this->securityScreening = $securityScreening;
    }

    public function getSecurityScreening(): ?string
    {
        return $this->securityScreening;
    }
}
