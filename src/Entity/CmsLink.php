<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmsLinkRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslatableInterface;

/**
 * @ORM\Entity(repositoryClass=CmsLinkRepository::class)
 * @ORM\Table(name="app_cms_link")
 */
class CmsLink implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=WebPage::class, inversedBy="cmsLinks")
     */
    private $webPage;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="cmsLinks")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=CmsMenu::class, inversedBy="cmsLinks")
     */
    private $cmsMenu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity=Component::class, inversedBy="cmsLinks")
     */
    private $component;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation()
    {
        return new CmsLinkTranslation();
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

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

    public function getCmsMenu(): ?CmsMenu
    {
        return $this->cmsMenu;
    }

    public function setCmsMenu(?CmsMenu $cmsMenu): self
    {
        $this->cmsMenu = $cmsMenu;

        return $this;
    }

    public function getLabel(): ?string
    {
       return $this->getTranslation()->getLabel();
        // return $this->getTranslation('fr_FR')->getLabel();
    }

    public function setLabel(string $label): self
    {
       $this->getTranslation()->setLabel($label);
        // $this->getTranslation('fr_FR')->setLabel($label);

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }
}
