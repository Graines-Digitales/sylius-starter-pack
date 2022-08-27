<?php

namespace App\Repository;

use Doctrine\ORM\ORMException;
use App\Entity\CmsLinkTranslation;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CmsLinkTranslation>
 *
 * @method CmsLinkTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsLinkTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsLinkTranslation[]    findAll()
 * @method CmsLinkTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsLinkTranslationRepository extends EntityRepository
{
   
}
