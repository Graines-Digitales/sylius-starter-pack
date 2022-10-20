<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Repository\OpeningHoursSpecificationRepository;


/**
 * @ORM\Entity(repositoryClass=OpeningHoursSpecificationRepository::class)
 * @ORM\Table(name="app_opening_hours_specification")
 */
class OpeningHoursSpecification
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $closes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $opens;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validFrom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validTrough;

    /**
     * @ORM\ManyToOne(targetEntity=LocalBusiness::class, inversedBy="openingHours", cascade={"persist"})
     */
    private $localBusiness;

    public function getCloses(): ?string
    {
        return $this->closes;
    }

    public function setCloses(?string $closes): self
    {
        $this->closes = $closes;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getOpens(): ?string
    {
        return $this->opens;
    }

    public function setOpens(?string $opens): self
    {
        $this->opens = $opens;

        return $this;
    }

    public function getValidFrom(): ?string
    {
        return $this->validFrom;
    }

    public function setValidFrom(?string $validFrom): self
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidTrough(): ?string
    {
        return $this->validTrough;
    }

    public function setValidTrough(?string $validTrough): self
    {
        $this->validTrough = $validTrough;

        return $this;
    }

    public function getLocalBusiness(): ?LocalBusiness
    {
        return $this->localBusiness;
    }

    public function setLocalBusiness(?LocalBusiness $localBusiness): self
    {
        $this->localBusiness = $localBusiness;

        return $this;
    }
}
