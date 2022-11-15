<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\CreativeWorkTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ComponentTranslationRepository::class)
 * @ORM\Table(name="app_component_translation")
 */
class ComponentTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

    /**
     * @ Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $components;

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getComponents(): ?string
    {
        return $this->components;
    }

    public function setComponents(?string $components): self
    {
        $this->components = $components;

        return $this;
    }

}
