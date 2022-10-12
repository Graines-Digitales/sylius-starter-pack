<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelRoomTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelRoomTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelRoomTranslation[]    findAll()
 * @method HotelRoomTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRoomTranslationRepository extends EntityRepository
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
