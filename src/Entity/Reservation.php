<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Describes a reservation for travel, dining or an event. Some reservations require tickets. \\n\\nNote: This type is for information about actual reservations, e.g. in confirmation emails or HTML pages with individual confirmations of reservations. For offers of tickets, restaurant reservations, flights, or rental cars, use \[\[Offer\]\].
 *
 * @see https://schema.org/Reservation
 * 
 * @ApiResource(iri="https://schema.org/Reservation")
 * @ORM\Table(name="app_reservation")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
{
    use IdentifiableTrait;

    /**
     * The date and time the reservation was booked.
     *
     * @see https://schema.org/bookingTime
     */
    #[ORM\Column(type: 'date', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/bookingTime')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $bookingTime = null;

    public function setBookingTime(?\DateTimeInterface $bookingTime): void
    {
        $this->bookingTime = $bookingTime;
    }

    public function getBookingTime(): ?\DateTimeInterface
    {
        return $this->bookingTime;
    }
}
