<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use App\Repository\DiscountCodeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity(repositoryClass=DiscountCodeRepository::class)
 *  @ORM\table(name="app_discount_code")
 * @ApiResource(
 *  iri="http://schema.org/DiscountCode",
 * itemOperations={
 *    "get",
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
 *  collectionOperations={
 *    "get",
 *    "post"={"security"="is_granted('ROLE_ADMIN')"}
 *  }
 * )
 */
class DiscountCode implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="date")
     */
    private $expirationDate;

    /**
     * @ORM\Column(type="text")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="discountCode")
     */
    private $trips;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->setDiscountCode($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getDiscountCode() === $this) {
                $trip->setDiscountCode(null);
            }
        }

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

}
