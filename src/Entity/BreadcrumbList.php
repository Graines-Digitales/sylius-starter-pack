<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * A BreadcrumbList is an ItemList consisting of a chain of linked Web pages, typically described using at least their URL and their name, and typically ending with the current page.\\n\\nThe \[\[position\]\] property is used to reconstruct the order of the items in a BreadcrumbList The convention is that a breadcrumb list has an \[\[itemListOrder\]\] of \[\[ItemListOrderAscending\]\] (lower values listed first), and that the first items in this list correspond to the "top" or beginning of the breadcrumb trail, e.g. with a site or section homepage. The specific values of 'position' are not assigned meaning for a BreadcrumbList, but they should be integers, e.g. beginning with '1' for the first item in the list.
 *
 * @see https://schema.org/BreadcrumbList
 */
# [ORM\Entity]
# [ApiResource(iri: 'https://schema.org/BreadcrumbList')]
class BreadcrumbList implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
