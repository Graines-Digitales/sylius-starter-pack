<?php

namespace App\Repository;

use App\Entity\SpecialAnnouncementTranslation;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method SpecialAnnouncementTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialAnnouncementTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialAnnouncementTranslation[]    findAll()
 * @method SpecialAnnouncementTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialAnnouncementTranslationRepository extends EntityRepository
{
    // /**
    //  * @return SpecialAnnouncementTranslation[] Returns an array of SpecialAnnouncementTranslation objects
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
    public function findOneBySomeField($value): ?SpecialAnnouncementTranslation
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
