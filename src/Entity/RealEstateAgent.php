<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\ImagesTrait;
use App\Entity\Traits\ThingTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * A real-estate agent.
 *
 * @see http://schema.org/RealEstateAgent Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/RealEstateAgent")
 * @ORM\Table(name="app_real_estate_agent")
 * @ORM\Entity(repositoryClass=RealEstateAgentRepository::class)
 */
class RealEstateAgent implements ResourceInterface
{
    use IdentifiableTrait;
    use ImagesTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @var string|null the price range of the business, for example ```$$$```
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/priceRange")
     */
    private $priceRange;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Accommodation", mappedBy="realEstateAgent")
     */
    private $accommodations;

    /**
     * @var Person|null The author of this content or rating. Please note that author is special in that HTML 5 provides a special mechanism for indicating authorship via the rel tag. That is equivalent to this and may be used interchangeably.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     */
    private $person;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, options={"comment":"Phone"}, nullable=true)
     *
     * @ Assert\NotBlank(
     *  message="veuillez entrer votre numéro de téléphone"
     * )
     * @ Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="votre numéro de téléphone est invalide"
     * )
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, options={"comment":"Email"}, nullable=true)
     *
     * @ Assert\Expression(
     *     "this.getEmail() == null && !this.getPhone() == null",
     *     message="s'il vous plaît, entrez email ou téléphone"
     * )
     *
     * @ Assert\Email(
     *  message = "cette adresse email est invalide"
     * )
     */
    private $email;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->accommodations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getPerson()->getLastname().' '.$this->getPerson()->getFirstname();
    }

    public function getName()
    {
        return ucfirst($this->getPerson()->getFirstname()).' '.strtoupper($this->getPerson()->getLastname());
    }

    public function setPriceRange(?string $priceRange): void
    {
        $this->priceRange = $priceRange;
    }

    public function getPriceRange(): ?string
    {
        return $this->priceRange;
    }

    public function setPerson(?Person $person): void
    {
        $this->person = $person;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return Collection|Accommodation[]
     */
    public function getAccommodations(): Collection
    {
        return $this->accommodations;
    }

    public function addAccommodation(Accommodation $accommodation): self
    {
        if (!$this->accommodations->contains($accommodation)) {
            $this->accommodations[] = $accommodation;
            $accommodation->setRentalPriceType($this);
        }

        return $this;
    }

    public function removeAccommodation(Accommodation $accommodation): self
    {
        if ($this->accommodations->contains($accommodation)) {
            $this->accommodations->removeElement($accommodation);
            // set the owning side to null (unless already changed)
            if ($accommodation->getRentalPriceType() === $this) {
                $accommodation->setRentalPriceType(null);
            }
        }

        return $this;
    }
}
