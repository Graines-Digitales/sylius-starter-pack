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
use App\Repository\WebPageTranslationRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * @ApiResource(iri="https://schema.org/WebPage",
 *    "get",
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
 *  collectionOperations={
 *    "get",
 *    "post"={"security"="is_granted('ROLE_ADMIN')"}
 *  }
 * )
 * @ORM\Entity(repositoryClass=WebPageTranslationRepository::class)
 * @ORM\Table(name="app_web_page_translation")
 */
class WebPageTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTranslatableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $components;

     /**
     * @Gedmo\Slug(fields={"headline"}, updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $structuredData = [];


    public function getComponents(): ?string
    {
        return $this->components;
    }

    public function setComponents(?string $components): self
    {
        $this->components = $components;

        return $this;
    }

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
        
        return $this->slug;
    }

    public function getStructuredData(): ?array
    {
        return $this->structuredData;
    }

    public function setStructuredData(?array $structuredData): self
    {
        $this->structuredData = $structuredData;

        return $this;
    }
}
