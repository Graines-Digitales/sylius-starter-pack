<?php

namespace App\Repository;

use App\Entity\OpeningHoursSpecification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OpeningHoursSpecification|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpeningHoursSpecification|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpeningHoursSpecification[]    findAll()
 * @method OpeningHoursSpecification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpeningHoursSpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpeningHoursSpecification::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(OpeningHoursSpecification $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(OpeningHoursSpecification $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return OpeningHoursSpecification[] Returns an array of OpeningHoursSpecification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpeningHoursSpecification
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
