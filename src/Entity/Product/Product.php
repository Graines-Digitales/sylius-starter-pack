<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Entity\LocalBusiness;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct
{
    /**
     * @ORM\OneToMany(targetEntity=LocalBusiness::class, mappedBy="products")
     */
    private $localBusinesses;

    public function __construct()
    {
        parent::__construct();
        $this->localBusinesses = new ArrayCollection();
    }

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }

    /**
     * @return Collection<int, LocalBusiness>
     */
    public function getLocalBusinesses(): Collection
    {
        return $this->localBusinesses;
    }

    public function addLocalBusiness(LocalBusiness $localBusiness): self
    {
        if (!$this->localBusinesses->contains($localBusiness)) {
            $this->localBusinesses[] = $localBusiness;
            $localBusiness->setProducts($this);
        }

        return $this;
    }

    public function removeLocalBusiness(LocalBusiness $localBusiness): self
    {
        if ($this->localBusinesses->removeElement($localBusiness)) {
            // set the owning side to null (unless already changed)
            if ($localBusiness->getProducts() === $this) {
                $localBusiness->setProducts(null);
            }
        }

        return $this;
    }
}
