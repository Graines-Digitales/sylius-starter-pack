<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Address;
use App\Entity\Traits\SeoTrait;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\ThingTrait;
use App\Entity\Traits\ImagesTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Traits\IdentifiableTrait;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * An organization such as a school, NGO, corporation, club, etc.
 *
 * @see http://schema.org/Organization Documentation on Schema.org
 *
 * @ApiResource(
 *  *  itemOperations={
 *    "put"={"security"="is_granted('ROLE_ADMIN') or object.author == user"},
 *    "delete"={"security"="is_granted('ROLE_ADMIN') or object.author == user"}
 *  },
  *     collectionOperations={
 *       "get"={
 *         "method"="GET",  
 *       },
 * "post"={"security"="is_granted('ROLE_ADMIN')"},
 *       "organization_configuration"={
 *         "method"= "GET",
 *         "path"= "/api/v2/organization/configuration",
 *         "controller"= OrganizationController::class     
 *       },
 *       "organization_media_encoding_formats"={
 *         "method"= "GET",
 *         "path"= "/api/v2/organization/media-encoding-formats",
 *         "controller"= OrganizationController::class 
 *       }
 *     }
 * )
 * @ApiResource()
 * @ ApiFilter(SearchFilter::class, properties={ "category.translations.slug": "exact", "slug": "exact" })
 * @ORM\Entity@ORM\Entity(repositoryClass=OrganizationRepository::class)
 * @ORM\Table(name="app_organization")
 */
class Organization implements ResourceInterface
{
    use IdentifiableTrait;
    use SeoTrait;
    use ImagesTrait;
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
     *  message="veuillez entrer votre numéro de téléphone"
     * )
     * @ Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="votre numéro de téléphone est invalide"
     * )
     *
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, options={"comment":"Email"}, nullable=true)
     * @ Assert\NotBlank(
     *      message="veuillez saisir un e-mail"
     * )
     * @ Assert\Email(
     *      message = "votre email est invalide"
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
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
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
     *  message="veuillez entrer votre numéro de téléphone portable"
     * )
     * @ Assert\Regex(
     *  pattern="/^(0)[0-9]{9}$/",
     *  match=true,
     *  message="votre numéro de téléphone portable est invalide"
     * )
     *
     */
    private $mobilePhone;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist"})
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=LocalBusiness::class, mappedBy="organization")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $localBusinesses;

    /**
     * @ORM\ManyToMany(targetEntity=Organization::class, inversedBy="organizations")
     * @ORM\JoinTable(name="app_social_link_organization")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $socialLinks;

    /**
     * @ORM\ManyToMany(targetEntity=Organization::class, mappedBy="socialLinks")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $organizations;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="parents")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="parent")
     * 
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(
     *    readableLink=true
     * )
     */
    private $parents;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\ManyToOne(targetEntity=MediaObjectIcon::class, inversedBy="organizations")
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPoint;

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @param Address $streetAddress
     *
     * @return Person
     */
    public function addAddress($streetAddress)
    {
        if ($this->addresses->contains($streetAddress)) {
            return;
        }

        $this->addresses->add($streetAddress);
    }

    /**
     * Remove address.
     *
     * @param Address $streetAddress
     */
    public function removeAddress($streetAddress)
    {
        if (!$this->addresses->contains($streetAddress)) {
            return;
        }

        $this->addresses->removeElement($streetAddress);
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

    public function getIcon(): ?MediaObjectIcon
    {
        return $this->icon;
    }

    public function setIcon(?MediaObjectIcon $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getAdditionalPhone(): ?string
    {
        return $this->additionalPhone;
    }

    public function setAdditionalPhone(?string $additionalPhone): self
    {
        $this->additionalPhone = $additionalPhone;

        return $this;
    }

    public function getContactPoint(): ?string
    {
        return $this->contactPoint;
    }

    public function setContactPoint(?string $contactPoint): self
    {
        $this->contactPoint = $contactPoint;

        return $this;
    }

}
