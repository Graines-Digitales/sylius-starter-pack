<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Entities that have a somewhat fixed, physical extension.
 *
 * @see http://schema.org/? Documentation on Schema.org
 *
 * @ApiResource()
 * @ORM\Table(name="app_document_media_object_type")
 * @ORM\Entity(repositoryClass="App\Repository\DocumentObjectTypeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DocumentMediaObjectType
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="")
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
