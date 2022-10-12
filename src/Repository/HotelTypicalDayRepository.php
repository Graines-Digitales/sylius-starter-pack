<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelTypicalDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelTypicalDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelTypicalDay[]    findAll()
 * @method HotelTypicalDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelTypicalDayRepository extends EntityRepository
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
