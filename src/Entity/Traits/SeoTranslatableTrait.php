<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait SeoTranslatableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=70, nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=160, nullable=true)
     */
    private $metaDescription;

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

}
