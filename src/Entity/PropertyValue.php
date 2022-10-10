<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Care;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * A property-value pair, e.g. representing a feature of a product or place. Use the 'name' property for the name of the property. If there is an additional human-readable version of the value, put that into the 'description' property.\\n\\n Always use specific schema.org properties when a) they exist and b) you can populate them. Using PropertyValue as a substitute will typically not trigger the same effect as using the original, specific property.
 *
 * @see http://schema.org/PropertyValue Documentation on Schema.org
 *
 * @ORM\Entity(repositoryClass=PropertyValueRepository::class)
 * @ApiResource(iri="http://schema.org/PropertyValue")
 * @ORM\Table(name="app_property_value")
 */
class PropertyValue implements ResourceInterface
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;

     /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float|null The value of the quantitative value or property value node.\\n\\n\* For \[\[QuantitativeValue\]\] and \[\[MonetaryAmount\]\], the recommended type for values is 'Number'.\\n\* For \[\[PropertyValue\]\], it can be 'Text;', 'Number', 'Boolean', or 'StructuredValue'.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/value")
     */
    private $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $valueReference;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valueMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valueMin;

    /**
     * @ ORM\ManyToOne(targetEntity=Organization::class, inversedBy="openingHours")
     */
    private $openingHoursOrganization;

    /**
     * @ORM\ManyToOne(targetEntity=WebPage::class, inversedBy="propertyValues")
     */
    private $webPage;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="propertyValues")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=LocalBusiness::class, inversedBy="openingHours")
     */
    private $localBusiness;


    public function __toString()
    {
        return $this->getName() . ' - ' . substr($this->getValue(), 0, 30);
    }
    
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set the value of Value.
     *
     * @return self
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the value of Value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getValueReference(): ?string
    {
        return $this->valueReference;
    }

    public function setValueReference(?string $valueReference): self
    {
        $this->valueReference = $valueReference;

        return $this;
    }

    public function getValueMax(): ?int
    {
        return $this->valueMax;
    }

    public function setValueMax(?int $valueMax): self
    {
        $this->valueMax = $valueMax;

        return $this;
    }

    public function getValueMin(): ?int
    {
        return $this->valueMin;
    }

    public function setValueMin(?int $valueMin): self
    {
        $this->valueMin = $valueMin;

        return $this;
    }

    // public function getOpeningHoursOrganization(): ?Organization
    // {
    //     return $this->openingHoursOrganization;
    // }

    // public function setOpeningHoursOrganization(?Organization $openingHoursOrganization): self
    // {
    //     $this->openingHoursOrganization = $openingHoursOrganization;

    //     return $this;
    // }

    public function getWebPage(): ?WebPage
    {
        return $this->webPage;
    }

    public function setWebPage(?WebPage $webPage): self
    {
        $this->webPage = $webPage;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getLocalBusiness(): ?LocalBusiness
    {
        return $this->localBusiness;
    }

    public function setLocalBusiness(?LocalBusiness $localBusiness): self
    {
        $this->localBusiness = $localBusiness;

        return $this;
    }

}
