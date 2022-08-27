<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


trait CreativeWorkTrait
{
    /**
     * @var string|null headline of the article
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/headline")
     * @ Assert\NotNull(groups="creative")
     * @ Assert\NotBlank(groups="creative")
     */
    private $headline;

    /**
     * @var string|null a secondary title of the CreativeWork
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/alternativeHeadline")
     */
    private $alternativeHeadline;

    /**
     * @var string|null the textual content of this CreativeWork
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty(iri="http://schema.org/text")
     */
    private $pushForward;

    /**
     * @var string|null the textual content of this CreativeWork
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/text")
     */
    private $text;

    /**
     * @var string|null the actual body of the article
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/text")
     */
    private $textResume;

    /**
     * @var \DateTimeInterface|null date of first broadcast/publication
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/datePublished")
     * @ Assert\Date
     * @Gedmo\Timestampable(on="create")
     */
    private $datePublished;

    /**
     * @var \DateTimeInterface|null Date the content expires and is no longer useful or available. For example a \[\[VideoObject\]\] or \[\[NewsArticle\]\] whose availability or relevance is time-limited, or a \[\[ClaimReview\]\] fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date.
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/expires")
     * @ Assert\Date
     */
    private $expire;

    public function setHeadline(?string $headline): void
    {
        $this->headline = $headline;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setAlternativeHeadline(?string $alternativeHeadline): void
    {
        $this->alternativeHeadline = $alternativeHeadline;
    }

    public function getAlternativeHeadline(): ?string
    {
        return $this->alternativeHeadline;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setPushForward(?string $pushForward): void
    {
        $this->pushForward = $pushForward;
    }

    public function getPushForward(): ?string
    {
        return $this->pushForward;
    }

    public function setDatePublished(?\DateTimeInterface $datePublished): void
    {
        $this->datePublished = $datePublished;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setExpire(?\DateTimeInterface $expire): void
    {
        $this->expire = $expire;
    }

    public function getExpire(): ?\DateTimeInterface
    {
        return $this->expire;
    }

    public function getTextResume(): ?string
    {
        return $this->textResume;
    }

    public function setTextResume(?string $textResume): void
    {
        $this->textResume = $textResume;
    }
}
