<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdentifiableTrait;
use App\Controller\Api\MessageController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * A single message from a sender to one or more organizations or people.
 *
 * @see http://schema.org/Message Documentation on Schema.org
 *
 * @ApiResource(
 *     collectionOperations={
 *       "get"={
 *         "method"="GET",  
 *       },
 *       "message_contact_create"={
 *         "method"= "POST",
 *         "path"= "/api/v2/message/contact/create",
 *         "controller"= MessageController::class     
 *       },
 *       "message_product_create"={
 *         "method"= "POST",
 *         "path"= "/api/v2/message/product/create",
 *         "controller"= MessageController::class     
 *       },
 *       "newsletter_create"={
 *         "method"= "POST",
 *         "path"= "/api/v2/newsletter/create",
 *         "controller"= MessageController::class 
 *       }
 *     }
 * )
 * @ORM\Entity()
 * @ORM\Table(name="app_message")
 */
class Message implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;
  
    /**
     * @var \DateTimeInterface|null the date/time at which the message was sent
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/dateSent")
     * @ Assert\DateTime
     */
    private $dateSent;


    /**
     * @var Organization|null A sub property of participant. The participant who is at the receiving end of the action.
     *
     * @ ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ApiProperty(iri="http://schema.org/recipient")
     */
    private $recipient;

    /**
     * @var Organization|null A sub property of participant. The participant who is at the sending end of the action.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @ApiProperty(iri="http://schema.org/sender")
     */
    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * 
     * @ Assert\NotBlank(message="veuillez saisir un sujet")
     */
    private $subject;

    /**
     * @var string|null the textual content of this CreativeWork
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/text")
     * 
     * @Assert\NotBlank(message="veuillez saisir un message")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origin;


    public function __construct()
    {
        
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setDateSent(?\DateTimeInterface $dateSent): void
    {
        $this->dateSent = $dateSent;
    }

    public function getDateSent(): ?\DateTimeInterface
    {
        return $this->dateSent;
    }

    public function setRecipient( $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setSender(?Person $sender): void
    {
        $this->sender = $sender;
    }

    public function getSender(): ?Person
    {
        return $this->sender;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

}
