<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use App\Repository\CmsLinkTranslationRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;

/**
 * @ORM\Entity(repositoryClass=CmsLinkTranslationRepository::class)
 * @ORM\Table(name="app_cms_link_translation")
 */
class CmsLinkTranslation extends AbstractTranslation implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
