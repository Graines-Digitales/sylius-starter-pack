<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\Api\MessageController;


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
 *       "newsletter_create"={
 *         "method"= "POST",
 *         "path"= "/api/v2/newsletter/create",
 *         "controller"= MessageController::class 
 *       }
 *     }
 * )
 * @ORM\Entity
 * @ORM\Table(name="app_message")
 */
class Message implements ResourceInterface
{
    use TimestampableEntity;
  
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ApiProperty(iri="http://schema.org/sender")
     */
    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Enter a subject please")
     */
    private $subject;

    /**
     * @var string|null the textual content of this CreativeWork
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/text")
     * 
     * @Assert\NotBlank(message="Enter a message please")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity=LocalBusiness::class, inversedBy="messages")
     * 
     * @Assert\NotEqualTo(
     *     value = "-1",
     *     message="Choose a local business please"
     * )
     */
    private $localBusiness;

    /**
     * @ORM\OneToMany(targetEntity=ImageMediaObject::class, mappedBy="message")
     * 
     * @Assert\File(
     *     maxSize = "20M",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf"
     *      },
     *     mimeTypesMessage = "Formats autorisés : pdf, png, jpeg, jpg, gif"
     * )
     */
    private $messageAttachments;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="messages")
     */
    private $event;

    public function __construct()
    {
        $this->messageAttachments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getLocalBusiness(): ?LocalBusiness
    {
        return $this->localBusiness;
    }

    public function setLocalBusiness(?LocalBusiness $localBusiness): self
    {
        $this->localBusiness = $localBusiness;

        return $this;
    }

    /**
     * @return Collection<int, ImageMediaObject>
     */
    public function getMessageAttachments(): Collection
    {
        return $this->messageAttachments;
    }

    public function addMessageAttachment(ImageMediaObject $messageAttachment): self
    {
        if (!$this->messageAttachments->contains($messageAttachment)) {
            $this->messageAttachments[] = $messageAttachment;
            $messageAttachment->setMessage($this);
        }

        return $this;
    }

    public function removeMessageAttachment(ImageMediaObject $messageAttachment): self
    {
        if ($this->messageAttachments->removeElement($messageAttachment)) {
            // set the owning side to null (unless already changed)
            if ($messageAttachment->getMessage() === $this) {
                $messageAttachment->setMessage(null);
            }
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

}
