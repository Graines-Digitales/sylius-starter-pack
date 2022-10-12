<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\MediaObjectImage;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;


trait ImagesTrait
{
   /**
     * @ORM\ManyToOne(targetEntity=MediaObjectImage::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $primaryImage;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectImage::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $secondaryImage;

    public function getPrimaryImage(): ?MediaObjectImage
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(?MediaObjectImage $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

        return $this;
    }

    public function getSecondaryImage(): ?MediaObjectImage
    {
        return $this->secondaryImage;
    }

    public function setSecondaryImage(?MediaObjectImage $secondaryImage): self
    {
        $this->secondaryImage = $secondaryImage;

        return $this;
    }
}
