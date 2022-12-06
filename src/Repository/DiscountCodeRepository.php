<?php

namespace App\Repository;

use App\Entity\DiscountCode;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<DiscountCode>
 *
 * @method DiscountCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountCode[]    findAll()
 * @method DiscountCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountCodeRepository extends EntityRepository
{
    // /**
    //  * @return DiscountCode[] Returns an array of DiscountCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiscountCode
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
