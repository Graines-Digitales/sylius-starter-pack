<?php

namespace App\Repository;

use App\Entity\SpecialAnnouncement;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @method SpecialAnnouncement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialAnnouncement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialAnnouncement[]    findAll()
 * @method SpecialAnnouncement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialAnnouncementRepository extends EntityRepository
{
    // /**
    //  * @return SpecialAnnouncement[] Returns an array of SpecialAnnouncement objects
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
    public function findOneBySomeField($value): ?SpecialAnnouncement
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
