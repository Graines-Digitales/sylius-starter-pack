<?php

namespace App\Repository;

use App\Entity\CmsStyle;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CmsStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsStyle[]    findAll()
 * @method CmsStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsStyleRepository extends EntityRepository
{

    // /**
    //  * @return CmsStyle[] Returns an array of CmsStyle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsStyle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
