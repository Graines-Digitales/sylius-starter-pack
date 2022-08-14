<?php

namespace App\Repository;

use App\Entity\CmsComponent;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CmsComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsComponent[]    findAll()
 * @method CmsComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsComponentRepository extends EntityRepository
{

    public function findByType($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', 'xxxxxxxxxxxxxxxxxxxx')
        ;
    }

/*     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsComponent::class);
    } */

    // /**
    //  * @return CmsComponent[] Returns an array of CmsComponent objects
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
    public function findOneBySomeField($value): ?CmsComponent
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
