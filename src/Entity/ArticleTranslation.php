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
use App\Repository\ArticleTranslationRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;


/**
 * @ApiResource(iri="https://schema.org/Article",
 * itemOperations={
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
 *  collectionOperations={
 *    "post"={"security"="is_granted('ROLE_ADMIN')"}
 *  }
 * )
 * @ORM\Entity(repositoryClass=ArticleTranslationRepository::class)
 * @ORM\Table(name="app_article_translation")
 */
class ArticleTranslation  extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTranslatableTrait;
    use ThingTrait;
    use CreativeWorkTrait;
    use TimestampableEntity;

    // /** 
    //  * @var TranslatableInterface|null 
    //  * 
    //  * @ApiProperty(
    //  *    readableLink=true
    //  * )
    // */
    // protected $translatable;

    /**
     * @Gedmo\Slug(fields={"headline"}, updatable=true)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

        /**
     * @var string|null the actual body of the article
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/articleBody")
     */
    private $articleBody;

    /**
     * @var string|null the actual body of the article
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/articleBody")
     */
    private $articleResume;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $components;


    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $structuredData = [];

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

    public function getArticleBody(): ?string
    {
        return $this->articleBody;
    }

    public function setArticleBody(?string $articleBody): self
    {
        $this->articleBody = $articleBody;

        return $this;
    }

    public function getArticleResume(): ?string
    {
        return $this->articleResume;
    }

    public function setArticleResume(?string $articleResume): self
    {
        $this->articleResume = $articleResume;

        return $this;
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
