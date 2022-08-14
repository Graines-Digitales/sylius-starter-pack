<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


trait SeoTrait
{
    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isEnabled = true;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isIndexed = true;

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getIsIndexed(): ?bool
    {
        return $this->isIndexed;
    }

    public function setIsIndexed(bool $isIndexed): self
    {
        $this->isIndexed = $isIndexed;

        return $this;
    }
}
