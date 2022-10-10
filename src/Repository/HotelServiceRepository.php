<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelService|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelService|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelService[]    findAll()
 * @method HotelService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelServiceRepository extends EntityRepository
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
