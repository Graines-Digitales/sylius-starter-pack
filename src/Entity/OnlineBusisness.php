<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see https://schema.org/Thing
 * 
 * @ApiResource(iri="https://schema.org/OnlineBusiness")
 * @ORM\Table(name="app_online_business")
 * @ORM\Entity(repositoryClass="App\Repository\OnlineBusinessRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OnlineBusisness
{
    use IdentifiableTrait;

    /**
     * The identifier property represents any kind of identifier for any kind of \[\[Thing\]\], such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See \[background notes\](/docs/datamodel.html#identifierBg) for more details.
     *
     * @see https://schema.org/identifier
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/identifier')]
    #[Assert\Url]
    private ?string $identifier = null;

    /**
     * A description of the item.
     *
     * @see https://schema.org/description
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/description')]
    private ?string $description = null;

    /**
     * An image of the item. This can be a \[\[URL\]\] or a fully described \[\[ImageObject\]\].
     *
     * @see https://schema.org/image
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/image')]
    #[Assert\Url]
    private ?string $image = null;

    /**
     * The name of the item.
     *
     * @see https://schema.org/name
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/name')]
    private ?string $name = null;

    /**
     * An alias for the item.
     *
     * @see https://schema.org/alternateName
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/alternateName')]
    private ?string $alternateName = null;

    /**
     * URL of a reference Web page that unambiguously indicates the item's identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or official website.
     *
     * @see https://schema.org/sameAs
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/sameAs')]
    #[Assert\Url]
    private ?string $sameAs = null;

    /**
     * A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.
     *
     * @see https://schema.org/disambiguatingDescription
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/disambiguatingDescription')]
    private ?string $disambiguatingDescription = null;

    /**
     * URL of the item.
     *
     * @see https://schema.org/url
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/url')]
    #[Assert\Url]
    private ?string $url = null;

    /**
     * An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. In RDFa syntax, it is better to use the native RDFa syntax - the 'typeof' attribute - for multiple types. Schema.org tools may have only weaker understanding of extra types, in particular those defined externally.
     *
     * @see https://schema.org/additionalType
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/additionalType')]
    #[Assert\Url]
    private ?string $additionalType = null;

    /**
     * Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See \[background notes\](/docs/datamodel.html#mainEntityBackground) for details.
     *
     * @see https://schema.org/mainEntityOfPage
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/mainEntityOfPage')]
    #[Assert\Url]
    private ?string $mainEntityOfPage = null;

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setAlternateName(?string $alternateName): void
    {
        $this->alternateName = $alternateName;
    }

    public function getAlternateName(): ?string
    {
        return $this->alternateName;
    }

    public function setSameAs(?string $sameAs): void
    {
        $this->sameAs = $sameAs;
    }

    public function getSameAs(): ?string
    {
        return $this->sameAs;
    }

    public function setDisambiguatingDescription(?string $disambiguatingDescription): void
    {
        $this->disambiguatingDescription = $disambiguatingDescription;
    }

    public function getDisambiguatingDescription(): ?string
    {
        return $this->disambiguatingDescription;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setAdditionalType(?string $additionalType): void
    {
        $this->additionalType = $additionalType;
    }

    public function getAdditionalType(): ?string
    {
        return $this->additionalType;
    }

    public function setMainEntityOfPage(?string $mainEntityOfPage): void
    {
        $this->mainEntityOfPage = $mainEntityOfPage;
    }

    public function getMainEntityOfPage(): ?string
    {
        return $this->mainEntityOfPage;
    }
}
