<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\AccommodationTypeTranslation;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_accommodation_type")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationType implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isLocation = false;

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
    protected function createTranslation(): TranslationInterface
    {
        return new AccommodationTypeTranslation();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->getTranslation()->getName();
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsLocation(): ?bool
    {
        return $this->isLocation;
    }

    public function setIsLocation(bool $isLocation): self
    {
        $this->isLocation = $isLocation;

        return $this;
    }
}
