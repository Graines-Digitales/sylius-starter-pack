<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\UserGroup;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_admin_user")
 */
class AdminUser extends BaseAdminUser
{
    /**
     * @ORM\ManyToOne(targetEntity=UserGroup::class, inversedBy="adminUsers")
     */
    // private $userGroup;

    // public function getUserGroup(): ?UserGroup
    // {
    //     return $this->userGroup;
    // }

    // public function setUserGroup(?UserGroup $userGroup): self
    // {
    //     $this->userGroup = $userGroup;

    //     return $this;
    // }
}
