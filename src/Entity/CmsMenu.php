<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmsMenuRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\CodeAwareInterface;


/**
 * @ORM\Entity(repositoryClass=CmsMenuRepository::class)
 * @ORM\Table(name="app_cms_menu")
 */
class CmsMenu implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isEnabled = true;

    /**
     * @Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=CmsMenu::class, inversedBy="menus")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $cmsMenu;

    /**
     * @ORM\OneToMany(targetEntity=CmsMenu::class, mappedBy="cmsMenu")
     */
    private $menus;

    /**
     * @ORM\OneToMany(targetEntity=CmsLink::class, mappedBy="cmsMenu", cascade={"persist", "remove"})
     */
    private $cmsLinks;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectImage::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $primaryImage;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->cmsLinks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {   
        $this->slug = $slug;
        
        return $this->slug;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCmsMenu(): ?self
    {
        return $this->cmsMenu;
    }

    public function setCmsMenu(?self $cmsMenu): self
    {
        $this->cmsMenu = $cmsMenu;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(self $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setCmsMenu($this);
        }

        return $this;
    }

    public function removeMenu(self $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getCmsMenu() === $this) {
                $menu->setCmsMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CmsLink>
     */
    public function getCmsLinks(): Collection
    {
        return $this->cmsLinks;
    }

    public function addCmsLink(CmsLink $cmsLink): self
    {
        if (!$this->cmsLinks->contains($cmsLink)) {
            $this->cmsLinks[] = $cmsLink;
            $cmsLink->setCmsMenu($this);
        }

        return $this;
    }

    public function removeCmsLink(CmsLink $cmsLink): self
    {
        if ($this->cmsLinks->removeElement($cmsLink)) {
            // set the owning side to null (unless already changed)
            if ($cmsLink->getCmsMenu() === $this) {
                $cmsLink->setCmsMenu(null);
            }
        }

        return $this;
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

    public function getPrimaryImage(): ?MediaObjectImage
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?MediaObjectImage $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

        return $this;
    }
}
