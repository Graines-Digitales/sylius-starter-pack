<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @TODO : à revoir selon le standard schema.org
 *
 * @ApiResource()
 * @ORM\Table(name="app_accommodation_detail_translation")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationDetailTranslationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationDetailTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getLabel().' => '.$this->getValue();
    }

    /**
     * Set the value of Label.
     *
     * @param mixed label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the value of Label.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of Value.
     *
     * @param mixed value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of Value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

}
