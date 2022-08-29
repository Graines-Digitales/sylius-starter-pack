<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ImageMediaObject;
use App\Entity\Traits\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * An organization such as a school, NGO, corporation, club, etc.
 *
 * @see http://schema.org/Organization Documentation on Schema.org
 *
 * @ApiResource()
 * @ORM\Entity@ORM\Entity(repositoryClass=OrganizationRepository::class)
 * @ORM\Table(name="app_organization")
 */
class Organization implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ThingTrait;
    use TimestampableEntity;
    
    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="legal_name", type="string", length=255, nullable=true)
     */
    private $legalName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, options={"comment":"Phone"}, nullable=true)
     *
     * @ Assert\NotBlank(
     *  message="Please enter your phone number"
     * )
     * @ Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="Your phone number is invalid"
     * )
     *
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, options={"comment":"Email"}, nullable=true)
     * @ Assert\NotBlank(
     *      message="Please enter an email"
     * )
     * @ Assert\Email(
     *      message = "Your email is invalid"
     * )
     *
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="foundingDate", type="date", nullable=true)
     *
     */
    private $foundingDate;

    /**
     * @ORM\ManyToMany(targetEntity="Address", inversedBy="organizations", cascade= { "persist"})
     * @ORM\JoinTable(
     *  name="app_organization_addresse",
     *  joinColumns={
     *      @ORM\JoinColumn(name="organisation_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     *  }
     *
     * )
     **/
    private $addresses;

    /**
     * @var int
     *
     * @ORM\Column(name="number_of_employees", type="integer", nullable=true)
     *
     */
    private $numberOfEmployees;

    /**
     * @var ImageMediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity=ImageMediaObject::class)
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $primaryImage;  

    /**
     * @var ImageMediaObject|null indicates the main image on the page
     *
     * @ORM\ManyToOne(targetEntity=ImageMediaObject::class)
     * @ApiProperty(iri="http://schema.org/primaryImage")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $secondaryImage;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     */
    private $numberOfProjects;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_phone", type="string", length=255, options={"comment":"Mobile Phone"}, nullable=true)
     *
     * @ Assert\NotBlank(
     *  message="Please enter your mobile phone number"
     * )
     * @ Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="Your mobile phone number is invalid"
     * )
     *
     */
    private $mobilePhone;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="organizations")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=LocalBusiness::class, mappedBy="organization")
     */
    private $localBusinesses;

    /**
     * @ORM\ManyToMany(targetEntity=Organization::class, inversedBy="organizations")
     * @ORM\JoinTable(name="app_social_link_organization")
     */
    private $socialLinks;

    /**
     * @ORM\ManyToMany(targetEntity=Organization::class, mappedBy="socialLinks")
     */
    private $organizations;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="parents")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="parent")
     */
    private $parents;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setPrimaryImage(?ImageMediaObject $primaryImage): void
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage(): ?ImageMediaObject
    {
        return $this->primaryImage;
    }

    public function setSecondaryImage(?ImageMediaObject $secondaryImage): void
    {
        $this->secondaryImage = $secondaryImage;
    }

    public function getSecondaryImage(): ?ImageMediaObject
    {
        return $this->secondaryImage;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->socialLinks = new ArrayCollection();
        $this->localBusinesses = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->parents = new ArrayCollection(); 
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getLegalName()
    {
        return $this->legalName;
    }

    /**
     * @param string $legalName
     */
    public function setLegalName($legalName)
    {
        $this->legalName = $legalName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getNumberOfEmployees()
    {
        return $this->numberOfEmployees;
    }

    /**
     * @param int $numberOfEmployees
     */
    public function setNumberOfEmployees($numberOfEmployees)
    {
        $this->numberOfEmployees = $numberOfEmployees;
    }

    /**
     * @return \DateTime
     */
    public function getFoundingDate()
    {
        return $this->foundingDate;
    }

    /**
     * @param \DateTime $foundingDate
     */
    public function setFoundingDate($foundingDate)
    {
        $this->foundingDate = $foundingDate;
    }

    public function getNumberOfProjects(): ?int
    {
        return $this->numberOfProjects;
    }

    public function setNumberOfProjects(?int $numberOfProjects): self
    {
        $this->numberOfProjects = $numberOfProjects;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

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

    /**
     * Add address.
     *
     * @param \App\Entity\Address $address
     *
     * @return Person
     */
    public function addAddress($address)
    {
        if ($this->addresses->contains($address)) {
            return;
        }

        $this->addresses->add($address);
    }

    /**
     * Remove address.
     *
     * @param \App\Entity\Address $address
     */
    public function removeAddress($address)
    {
        if (!$this->addresses->contains($address)) {
            return;
        }

        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    public function getOrganization(): ?self
    {
        return $this->organization;
    }

    public function setOrganization(?self $organization): self
    {
        $this->organization = $organization;

        return $this;
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
            $localBusiness->setOrganization($this);
        }

        return $this;
    }

    public function removeLocalBusiness(LocalBusiness $localBusiness): self
    {
        if ($this->localBusinesses->removeElement($localBusiness)) {
            // set the owning side to null (unless already changed)
            if ($localBusiness->getOrganization() === $this) {
                $localBusiness->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function addSocialLink(self $socialLink): self
    {
        if (!$this->socialLinks->contains($socialLink)) {
            $this->socialLinks[] = $socialLink;
        }

        return $this;
    }

    public function removeSocialLink(self $socialLink): self
    {
        $this->socialLinks->removeElement($socialLink);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(self $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
            $organization->addSocialLink($this);
        }

        return $this;
    }

    public function removeOrganization(self $organization): self
    {
        if ($this->organizations->removeElement($organization)) {
            $organization->removeSocialLink($this);
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(self $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
            $parent->setParent($this);
        }

        return $this;
    }

    public function removeParent(self $parent): self
    {
        if ($this->parents->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getParent() === $this) {
                $parent->setParent(null);
            }
        }

        return $this;
    }
    
    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

}
