<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method HotelServiceTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelServiceTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelServiceTranslation[]    findAll()
 * @method HotelServiceTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelServiceTranslationRepository extends EntityRepository
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
