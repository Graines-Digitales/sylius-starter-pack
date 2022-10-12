<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelActivity[]    findAll()
 * @method HotelActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelActivityRepository extends EntityRepository
{
    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
