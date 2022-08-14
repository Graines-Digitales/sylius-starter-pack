<?php

namespace App\Repository;

use App\Entity\PropertyValue;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method PropertyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyValue[]    findAll()
 * @method PropertyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyValueRepository extends EntityRepository
{
    /*
    public function findOneBySomeField($value): ?PropertyValue
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
