<?php

namespace App\Repository;

use App\Entity\CmsLink;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CmsLink>
 *
 * @method CmsLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsLink[]    findAll()
 * @method CmsLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsLinkRepository extends EntityRepository
{

}
