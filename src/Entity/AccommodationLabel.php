<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * @TODO : à revoir selon le standard schema.org
 * 
 * @ApiResource()
 * @ORM\Table(name="app_accommodation_label")
 * @ORM\Entity(repositoryClass="App\Repository\AccommodationLabelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AccommodationLabel implements ResourceInterface, TranslatableInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }
    
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
        return new AccommodationLabelTranslation();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->getTranslation()->getName();
    }
}
