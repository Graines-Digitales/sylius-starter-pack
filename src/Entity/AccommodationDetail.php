<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * @TODO : à revoir selon le standard schema.org
 *
 * @ApiResource()
 * @ORM\Table(name="app_accommodation_detail")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationDetailRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationDetail implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="details")
     * @ORM\JoinColumn(name="accommodation_id", referencedColumnName="id")
     */
    private $accommodation;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }


    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new AmenityFeatureTranslation();
    }

    public function __toString()
    {
        return $this->getLabel().' => '.$this->getValue();
    }

    /**
     * Get the value of Label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->getTranslation()->getLabel();
    }

    /**
     * Get the value of Value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getTranslation()->getValue();
    }

    public function getAccommodation(): ?Accommodation
    {
        return $this->accommodation;
    }

    public function setAccommodation(?Accommodation $accommodation): self
    {
        $this->accommodation = $accommodation;

        return $this;
    }
}
