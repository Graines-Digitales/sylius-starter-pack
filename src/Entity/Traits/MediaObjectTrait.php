<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;


trait MediaObjectTrait
{
    /**
     * @var int|null file size in (mega/kilo) bytes
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ApiProperty(iri="http://schema.org/contentSize")
     */
    private $contentSize;

    /**
     * @var string|null mp3, mpeg4, etc
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/encodingFormat")
     */
    private $encodingFormat;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ Assert\NotNull
     */
    private $originalFilename;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=true)
     * @ Assert\NotNull
     */
    private $dimensions;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tmpFile;

    public function setContentSize($contentSize): void
    {
        $this->contentSize = $contentSize;
    }

    public function getContentSize()
    {
        return $this->contentSize;
    }

    public function setEncodingFormat(?string $encodingFormat): void
    {
        $this->encodingFormat = $encodingFormat;
    }

    public function getEncodingFormat(): ?string
    {
        return $this->encodingFormat;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Set the value of Original Name.
     *
     * @param string originalFilename
     *
     * @return self
     */
    public function setOriginalFilename(?string $originalFilename): void
    {
        $this->originalFilename = $originalFilename;
    }

    /**
     * Get the value of Original Name.
     *
     * @return string
     */
    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    /**
     * Set the value of Dimensions.
     *
     * @param string dimensions
     *
     * @return self
     */
    public function setDimensions(?array $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    /**
     * Get the value of Dimensions.
     *
     * @return string
     */
    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }
  
    /*
    * Set tmpFile
    * @return Image
    */
    public function setTmpFile($tmpFile)
    {
        $this->tmpFile = $tmpFile;

        return $this;
    }

    /*
    * Get tmpFile
    * @return string
    */
    public function getTmpFile()
    {
        return $this->tmpFile;
    }

    /**
     * Set the value of Caption.
     *
     * @param string|null caption
     *
     * @return self
     */
    public function setCaption(?string $caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get the value of Caption.
     *
     * @return string|null
     */
    public function getCaption()
    {
        return $this->caption;
    }

}
