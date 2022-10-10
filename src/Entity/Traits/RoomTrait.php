<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait RoomTrait
{
    /**
     * @ORM\Column(name="maximumOccupants", type="smallint", nullable=true)
     *
     * @ Assert\NotBlank(message="Enter the number of occupants")
     */
    private $maximumOccupants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $labelBgTransparent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfRooms;

    /**
     * @ORM\Column(type="smallint")
     */
    private $minimumOccupants;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the value of MaximumOccupants.
     *
     * @return int
     */
    public function getMaximumOccupants()
    {
        return $this->maximumOccupants;
    }

    /**
     * Set the value of MaximumOccupants.
     *
     * @param int MaximumOccupants
     *
     * @return self
     */
    public function setMaximumOccupants($maximumOccupants)
    {
        $this->maximumOccupants = $maximumOccupants;

        return $this;
    }

    public function getNumberOfRooms(): ?int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(?int $numberOfRooms): self
    {
        $this->numberOfRooms = $numberOfRooms;

        return $this;
    }

    public function getMinimumOccupants(): ?int
    {
        return $this->minimumOccupants;
    }

    public function setMinimumOccupants(int $minimumOccupants): self
    {
        $this->minimumOccupants = $minimumOccupants;

        return $this;
    }

    public function getLabelBgTransparent(): ?bool
    {
        return $this->labelBgTransparent;
    }

    public function setLabelBgTransparent(bool $labelBgTransparent): self
    {
        $this->labelBgTransparent = $labelBgTransparent;

        return $this;
    }
}
