<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmsTemplateRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;

/**
 * @ORM\Entity(repositoryClass=CmsTemplateRepository::class)
 * @ORM\Table(name="app_cms_template")
 */
class CmsTemplate implements ResourceInterface, CodeAwareInterface
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
     * @ORM\ManyToOne(targetEntity=CmsComponent::class, inversedBy="templates")
     * @ORM\JoinColumn(name="cms_component_id", referencedColumnName="id")
     */
    private $cmsComponent;

    public function __toString()
    {
        return $this->getName();
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

    public function getCmsComponent(): ?CmsComponent
    {
        return $this->cmsComponent;
    }

    public function setCmsComponent(?CmsComponent $cmsComponent): self
    {
        $this->cmsComponent = $cmsComponent;

        return $this;
    }
}
