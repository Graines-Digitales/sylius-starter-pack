<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SearchActionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SearchActionRepository::class)
 * @ORM\Table(name="app_search_action")
 */
class SearchAction implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainEntityOfPage;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $query = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderByDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $limitResult;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="searchActions")
     * @ORM\JoinTable(name="app_category_searchaction")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMainEntityOfPage(): ?string
    {
        return $this->mainEntityOfPage;
    }

    public function setMainEntityOfPage(string $mainEntityOfPage): self
    {
        $this->mainEntityOfPage = $mainEntityOfPage;

        return $this;
    }

    public function getQuery(): ?array
    {
        return $this->query;
    }

    public function setQuery(?array $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getOrderByDate(): ?string
    {
        return $this->orderByDate;
    }

    public function setOrderByDate(string $orderByDate): self
    {
        $this->orderByDate = $orderByDate;

        return $this;
    }

    public function getLimitResult(): ?string
    {
        return $this->limitResult;
    }

    public function setLimitResult(?string $limitResult): self
    {
        $this->limitResult = $limitResult;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Category $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Category $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
