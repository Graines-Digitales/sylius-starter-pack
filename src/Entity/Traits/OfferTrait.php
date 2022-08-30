<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;


trait OfferTrait
{
    /**
     * @var string|null the name of the item
     *
     * @ORM\Column(type="string", length=255)
     * @ApiProperty(iri="http://schema.org/name")
     */
    private $name;

    /**
     * @var float|null The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.\\n\\nUsage guidelines:\\n\\n\* Use the \[\[priceCurrency\]\] property (with \[ISO 4217 codes\](http://en.wikipedia.org/wiki/ISO\_4217#Active\_codes) e.g. "USD") instead of including \[ambiguous symbols\](http://en.wikipedia.org/wiki/Dollar\_sign#Currencies\_that\_use\_the\_dollar\_or\_peso\_sign) such as '$' in the value.\\n\* Use '.' (Unicode 'FULL STOP' (U+002E)) rather than ',' to indicate a decimal point. Avoid using these symbols as a readability separator.\\n\* Note that both \[RDFa\](http://www.w3.org/TR/xhtml-rdfa-primer/#using-the-content-attribute) and Microdata syntax allow the use of a "content=" attribute for publishing simple machine-readable values alongside more human-friendly formatting.\\n\* Use values from 0123456789 (Unicode 'DIGIT ZERO' (U+0030) to 'DIGIT NINE' (U+0039)) rather than superficially similar Unicode symbols.
     *
     * @ORM\Column(type="float", nullable=true)
     * @ApiProperty(iri="http://schema.org/price")
     */
    private $price;

    /**
     * @var string|null the currency (in 3-letter ISO 4217 format) of the price or a price component, when attached to \[\[PriceSpecification\]\] and its subtypes
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/priceCurrency")
     */
    private $priceCurrency;

    /**
     * @var \DateTimeInterface|null the end of the availability of the product or service included in the offer
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/availabilityEnds")
     *
     * @ Assert\DateTime
     */
    private $availabilityEnd;

    /**
     * @var \DateTimeInterface|null the beginning of the availability of the product or service included in the offer
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/availabilityStarts")
     *
     * @ Assert\DateTime
     */
    private $availabilityStart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param float|null $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function setPriceCurrency(?string $priceCurrency): void
    {
        $this->priceCurrency = $priceCurrency;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setAvailabilityEnd(?\DateTime $availabilityEnd): void
    {
        $this->availabilityEnd = $availabilityEnd;
    }

    public function getAvailabilityEnd(): ?\DateTime
    {
        return $this->availabilityEnd;
    }

    public function setAvailabilityStart(?\DateTime $availabilityStart): void
    {
        $this->availabilityStart = $availabilityStart;
    }

    public function getAvailabilityStart(): ?\DateTime
    {
        return $this->availabilityStart;
    }
}
