<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\SeoTranslatableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Entity(repositoryClass=HotelActivityTranslationRepository::class)
 * @ORM\Table(name="app_hotel_activity_translation")
 */
class HotelActivityTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTranslatableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
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
     * @Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    public function getSlug()
    {
        return $this->slug;
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
