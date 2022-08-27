<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * A list of items of any sort—for example, Top 10 Movies About Weathermen, or Top 100 Party Songs. Not to be confused with HTML lists, which are often used only for formatting.
 *
 * @see https://schema.org/ItemList
 */
# [ORM\Entity]
# [ApiResource(iri: 'https://schema.org/ItemList')]
class ItemList
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * For itemListElement values, you can use simple strings (e.g. "Peter", "Paul", "Mary"), existing entities, or use ListItem.\\n\\nText values are best if the elements in the list are plain strings. Existing entities are best for a simple, unordered list of existing things in your data. ListItem is used with ordered lists when you want to provide additional context about the element in that list or when the same item might be in different places in different lists.\\n\\nNote: The order of elements in your mark-up is not sufficient for indicating the order or elements. Use ListItem with a 'position' property in such cases.
     *
     * @see https://schema.org/itemListElement
     */
    #[ORM\ManyToOne(targetEntity: 'App\Entity\Thing')]
    #[ApiProperty(iri: 'https://schema.org/itemListElement')]
    private ?Thing $itemListElement = null;

    /**
     * The number of items in an ItemList. Note that some descriptions might not fully describe all items in a list (e.g., multi-page pagination); in such cases, the numberOfItems would be for the entire list.
     *
     * @see https://schema.org/numberOfItems
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/numberOfItems')]
    private ?int $numberOfItems = null;

    /**
     * Type of ordering (e.g. Ascending, Descending, Unordered).
     *
     * @see https://schema.org/itemListOrder
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/itemListOrder')]
    private ?string $itemListOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setItemListElement(?Thing $itemListElement): void
    {
        $this->itemListElement = $itemListElement;
    }

    public function getItemListElement(): ?Thing
    {
        return $this->itemListElement;
    }

    public function setNumberOfItems(?int $numberOfItems): void
    {
        $this->numberOfItems = $numberOfItems;
    }

    public function getNumberOfItems(): ?int
    {
        return $this->numberOfItems;
    }

    public function setItemListOrder(?string $itemListOrder): void
    {
        $this->itemListOrder = $itemListOrder;
    }

    public function getItemListOrder(): ?string
    {
        return $this->itemListOrder;
    }
}
