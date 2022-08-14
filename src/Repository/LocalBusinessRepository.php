<?php

namespace App\Repository;

use App\Entity\LocalBusiness;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @method LocalBusiness|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocalBusiness|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocalBusiness[]    findAll()
 * @method LocalBusiness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalBusinessRepository extends EntityRepository
{
    // /**
    //  * @return LocalBusiness[] Returns an array of LocalBusiness objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LocalBusiness
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
