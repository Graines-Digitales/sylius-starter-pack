<?php

namespace App\Entity;

use App\Entity\User\AdminUser;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\CodeAwareInterface;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 * @ORM\Table(name="app_user_group")
 */
class UserGroup implements ResourceInterface, CodeAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ ORM\OneToMany(targetEntity=AdminUser::class, mappedBy="userGroup")
     */
    private $adminUsers;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    public function __construct()
    {
        $this->adminUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|AdminUser[]
     */
    public function getAdminUsers(): Collection
    {
        return $this->adminUsers;
    }

    public function addAdminUser(AdminUser $adminUser): self
    {
        if (!$this->adminUsers->contains($adminUser)) {
            $this->adminUsers[] = $adminUser;
            $adminUser->setUserGroup($this);
        }

        return $this;
    }

    public function removeAdminUser(AdminUser $adminUser): self
    {
        if ($this->adminUsers->removeElement($adminUser)) {
            // set the owning side to null (unless already changed)
            if ($adminUser->getUserGroup() === $this) {
                $adminUser->setUserGroup(null);
            }
        }

        return $this;
    }

/*     public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    } */

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }
}
