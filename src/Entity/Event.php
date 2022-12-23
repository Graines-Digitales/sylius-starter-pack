<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\HotelRoom;
use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;


/**
 * An event happening at a certain time and location, such as a concert, lecture, or festival. Ticketing information may be added via the \[\[offers\]\] property. Repeated events may be structured as separate Event objects.
 *
 * @see http://schema.org/Event Documentation on Schema.org
 *
 * @ApiResource(iri="http://schema.org/Event")
 * @ORM\Entity()
 * @ORM\Table(name="app_event")
 */
class Event implements ResourceInterface, TranslatableInterface
{
    use SeoTrait;
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_at", type="datetime")
     *
     * @Assert\NotBlank(message="entrez une date de début de location")
     */
    private $beginAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime")
     *
     * @Assert\NotBlank(message="entrez une date de fin de location")
     */
    private $endAt;

    /**
     * @ORM\ManyToOne(targetEntity="Accommodation", inversedBy="rentals")
     * @ORM\JoinColumn(name="accommodation_id", referencedColumnName="id", nullable=true)
     *
     * @ Assert\NotBlank(message="sélectionnez un logement")
     */
    private $accommodation;

    /**
     * One Event has One Location.
     *
     * @ORM\ManyToOne(targetEntity="RentalType")
     * @ORM\JoinColumn(name="rental_type_id", referencedColumnName="id")
     *
     * @ Assert\NotBlank(message="sélectionnez un type de location")
     */
    private $rentalType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $depositAmount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $depositStatus = false;

    /**
     * @ORM\ManyToOne(targetEntity="MediaObjectDocument")
     */
    private $rentalAgreement;

    /**
     * @Vich\UploadableField(mapping="uploads_document_files", fileNameProperty="document")
     *
     * @Assert\File(
     *     maxSize = "8M",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     * @Groups("event")
     */
    private $documentFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccommodationNature", inversedBy="events")
     */
    private $accommodationNature;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceTracking", mappedBy="event", cascade= { "remove" })
     */
    private $trackings;

    /**
     * @ORM\ManyToMany(targetEntity=HotelRoom::class, mappedBy="events")
     */
    private $rooms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $respondAt;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $numberOfAdults;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $numberOfChildren;

    /**
     * @ORM\OneToMany(targetEntity=HotelTypicalDayElement::class, mappedBy="event")
     */
    private $hotelTypicalDayElements;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="events")
     */
    private $person;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="events")
     * @ORM\JoinTable(name="app_events_categories")
     */
    private $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->rooms = new ArrayCollection();
        $this->hotelTypicalDayElements = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new EventTranslation();
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return event
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTimeInterface $beginAt = null): void
    {
        $this->beginAt = $beginAt;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt = null): void
    {
        $this->endAt = $endAt;
    }

    /**
     * @return mixed
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param mixed $accommodation
     *
     * @return \App\Entity\Accommodation
     */
    public function setAccommodation(Accommodation $accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * Set the value of One Event has One Location.
     *
     * @param mixed rentalType
     *
     * @return self
     */
    public function setRentalType($rentalType)
    {
        $this->rentalType = $rentalType;

        return $this;
    }

    /**
     * Get the value of One Event has One Location.
     *
     * @return mixed
     */
    public function getRentalType()
    {
        return $this->rentalType;
    }

    public function getDepositAmount(): ?string
    {
        return $this->depositAmount;
    }

    public function setDepositAmount(?string $depositAmount): self
    {
        $this->depositAmount = $depositAmount;

        return $this;
    }

    public function getDepositStatus(): ?bool
    {
        return $this->depositStatus;
    }

    public function setDepositStatus(?bool $depositStatus): self
    {
        $this->depositStatus = $depositStatus;

        return $this;
    }

    public function getRentalAgreement(): ?MediaObjectDocument
    {
        return $this->rentalAgreement;
    }

    public function setRentalAgreement(?MediaObjectDocument $rentalAgreement): void
    {
        $this->rentalAgreement = $rentalAgreement;
    }

    public function getAccommodationNature(): ?AccommodationNature
    {
        return $this->accommodationNature;
    }

    public function setAccommodationNature(?AccommodationNature $accommodationNature): self
    {
        $this->accommodationNature = $accommodationNature;

        return $this;
    }

    /**
     * Set the value of Document.
     *
     * @param File document
     *
     * @return self
     */
    public function setDocument(File $document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get the value of Document.
     *
     * @return File
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return File
     */
    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * @param File $documentFile
     */
    public function setDocumentFile($documentFile)
    {
        $this->documentFile = $documentFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($documentFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getTrackings()
    {
        return $this->trackings;
    }

    /**
     * @param \App\Entity\InvoiceTracking $tracking
     */
    public function addTracking($tracking)
    {
        if ($this->trackings->contains($tracking)) {
            return;
        }

        $tracking->addEvent($this);
        $this->trackings->add($tracking);
    }

    /**
     * @param \App\Entity\InvoiceTracking $tracking
     */
    public function removeTracking($tracking)
    {
        if (!$this->trackings->contains($tracking)) {
            return;
        }

        $this->trackings->removeElement($tracking);
        $tracking->removeEvent($this);
    }

     /**
     * @return Collection|HotelRoom[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(HotelRoom $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(HotelRoom $room): self
    {
        $this->rooms->removeElement($room);

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRespondAt(): ?\DateTimeInterface
    {
        return $this->respondAt;
    }

    public function setRespondAt(\DateTimeInterface $respondAt = null): void
    {
        $this->respondAt = $respondAt;
    }

    public function getNumberOfAdults(): ?int
    {
        return $this->numberOfAdults;
    }

    public function setNumberOfAdults(?int $numberOfAdults): self
    {
        $this->numberOfAdults = $numberOfAdults;

        return $this;
    }

    public function getNumberOfChildren(): ?int
    {
        return $this->numberOfChildren;
    }

    public function setNumberOfChildren(?int $numberOfChildren): self
    {
        $this->numberOfChildren = $numberOfChildren;

        return $this;
    }

    /**
     * @return Collection|HotelTypicalDayElement[]
     */
    public function getHotelTypicalDayElements(): Collection
    {
        return $this->hotelTypicalDayElements;
    }

    public function addHotelTypicalDayElement(HotelTypicalDayElement $hotelTypicalDayElement): self
    {
        if (!$this->hotelTypicalDayElements->contains($hotelTypicalDayElement)) {
            $this->hotelTypicalDayElements[] = $hotelTypicalDayElement;
            $hotelTypicalDayElement->setEvent($this);
        }

        return $this;
    }

    public function removeHotelTypicalDayElement(HotelTypicalDayElement $hotelTypicalDayElement): self
    {
        if ($this->hotelTypicalDayElements->removeElement($hotelTypicalDayElement)) {
            // set the owning side to null (unless already changed)
            if ($hotelTypicalDayElement->getEvent() === $this) {
                $hotelTypicalDayElement->setEvent(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Category $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Category $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
