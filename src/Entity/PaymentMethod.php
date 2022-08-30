<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\IdentifiableTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * PaymentMethod.
 *
 * @ApiResource()
 * @ORM\Table(name="app_payment_method")
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMethodRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PaymentMethod
{
    use IdentifiableTrait;
    use ThingTrait;
    use TimestampableEntity;
    
    /**
     * @Gedmo\Slug(fields={"name"}, prefix="", updatable=false)
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @ApiProperty(identifier=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="paymentMethod")
     */
    private $invoices;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @param mixed $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }
}
