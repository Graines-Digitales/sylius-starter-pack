<?php

namespace App\Repository;

use App\Entity\WebPageTranslation;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @method WebPageTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebPageTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebPageTranslation[]    findAll()
 * @method WebPageTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebPageTranslationRepository extends EntityRepository
{
/*     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebPageTranslation::class);
    }
 */
    // /**
    //  * @return WebPageTranslation[] Returns an array of WebPageTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebPageTranslation
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
