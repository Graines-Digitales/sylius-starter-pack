<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmsComponentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;

/**
 * @ORM\Entity(repositoryClass=CmsComponentRepository::class)
 * @ORM\Table(name="app_cms_component")
 */
class CmsComponent implements ResourceInterface, CodeAwareInterface
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=CmsTemplate::class, mappedBy="cmsComponent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $templates;

    /**
     * @ORM\OneToMany(targetEntity=CmsStyle::class, mappedBy="cmsComponent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $styles;

    public function __construct()
    {
        $this->templates = new ArrayCollection();
        $this->styles = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return Collection|CmsTemplate[]
     */
    public function getTemplates(): Collection
    {
        return $this->templates;
    }

    public function addTemplate(CmsTemplate $template): self
    {
        if (!$this->templates->contains($template)) {
            $this->templates[] = $template;
            $template->setCmsComponent($this);
        }

        return $this;
    }

    public function removeTemplate(CmsTemplate $template): self
    {
        if ($this->templates->removeElement($template)) {
            // set the owning side to null (unless already changed)
            if ($template->getCmsComponent() === $this) {
                $template->setCmsComponent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CmsStyle[]
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(CmsStyle $style): self
    {
        if (!$this->styles->contains($style)) {
            $this->styles[] = $style;
            $style->setCmsComponent($this);
        }

        return $this;
    }

    public function removeStyle(CmsStyle $style): self
    {
        if ($this->styles->removeElement($style)) {
            // set the owning side to null (unless already changed)
            if ($style->getCmsComponent() === $this) {
                $style->setCmsComponent(null);
            }
        }

        return $this;
    }
}
