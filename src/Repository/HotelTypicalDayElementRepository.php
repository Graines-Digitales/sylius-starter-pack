<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\HotelTypicalDayElement;
use App\Entity\HotelTypicalDayElementTranslation;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelTypicalDayElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelTypicalDayElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelTypicalDayElement[]    findAll()
 * @method HotelTypicalDayElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelTypicalDayElementRepository extends EntityRepository
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
