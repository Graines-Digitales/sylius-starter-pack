<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderTripRepository;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * An order is a confirmation of a transaction (a receipt), which can contain multiple line items, each represented by an Offer that has been accepted by the customer.
 *
 * @see http://schema.org/Order Documentation on Schema.org
 *
 * @ORM\Entity(repositoryClass=OrderTripRepository::class)
 * @ORM\table(name="app_order_trip")
 * @ApiResource(
 *  iri="http://schema.org/Order",
 *  collectionOperations={
 *       "get",
 *       "post"={"security"="is_granted('ROLE_ADMIN')"},
 *       "payment_create"={
 *         "method"= "POST",
 *         "path"= "/api/v2/payment/create",
 *         "controller"= PaymentController::class 
 *       },
 *       "payment_sucess"={
 *         "method"= "POST",
 *         "path"= "/api/v2/payment/sucess",
 *         "controller"= PaymentController::class 
 *       }
 *   },
 *   itemOperations={
 *       "get",
 *       "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *       "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *   }
 * )
 */
class OrderTrip implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableEntity;

    /**
     * @var string|null a number that confirms the given order or payment has been received
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/confirmationNumber")
     */
    private $confirmationNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, cascade= {"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Trip::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $orderItem;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acceptedOffer;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="float")
     */
    private $discount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $discountCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paymentSplit;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderQuantity;

    public function setConfirmationNumber(?string $confirmationNumber): void
    {
        $this->confirmationNumber = $confirmationNumber;
    }

    public function getConfirmationNumber(): ?string
    {
        return $this->confirmationNumber;
    }

    public function getCustomer(): ?Person
    {
        return $this->customer;
    }

    public function setCustomer(?Person $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?string $orderNumber): self
    {
        $this->orderNumber = $this->getId();

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getOrderItem(): ?Trip
    {
        return $this->orderItem;
    }

    public function setOrderItem(?Trip $orderItem): self
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    public function getAcceptedOffer(): ?string
    {
        return $this->acceptedOffer;
    }

    public function setAcceptedOffer(string $acceptedOffer): self
    {
        $this->acceptedOffer = $acceptedOffer;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscountCode(): ?string
    {
        return $this->discountCode;
    }

    public function setDiscountCode(string $discountCode): self
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    public function getPaymentSplit(): ?bool
    {
        return $this->paymentSplit;
    }

    public function setPaymentSplit(bool $paymentSplit): self
    {
        $this->paymentSplit = $paymentSplit;

        return $this;
    }

    public function getOrderQuantity(): ?int
    {
        return $this->orderQuantity;
    }

    public function setOrderQuantity(int $orderQuantity): self
    {
        $this->orderQuantity = $orderQuantity;

        return $this;
    }
}
