<?php

namespace App\Entity;

use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ImagesTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\AdministrableTrait;
use App\Entity\Traits\TimestampableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_hotel_typical_day_element")
 * @ORM\Entity(repositoryClass="App\Repository\HotelTypicalDayElementRepository")
 */
class HotelTypicalDayElement implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ImagesTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }


    /**
     * @ORM\ManyToOne(targetEntity="HotelTypicalDay", inversedBy="elements", cascade={"persist"})
     * @ORM\JoinColumn(name="hotel_typical_day_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $hotelTypicalDay;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hours;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="hotelTypicalDayElements")
     */
    private $event;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups("hotel_typical_day")
     */
    private $labelBgTransparent;


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
        return new HotelTypicalDayElementTranslation();
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

    /**
     * Set HotelTypicalDay.
     *
     * @param \App\Entity\HotelTypicalDay $hotelTypicalDay
     *
     * @return HotelTypicalDay
     */
    public function setHotelTypicalDay(HotelTypicalDay $hotelTypicalDay = null)
    {
        $this->hotelTypicalDay = $hotelTypicalDay;

        return $this;
    }

    /**
     * Get HotelTypicalDay.
     *
     * @return \App\Entity\HotelTypicalDay
     */
    public function getHotelTypicalDay()
    {
        return $this->hotelTypicalDay;
    }

    public function getHours(): ?string
    {
        return $this->hours;
    }

    public function setHours(string $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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
