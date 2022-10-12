<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method Accommodation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accommodation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accommodation[]    findAll()
 * @method Accommodation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccommodationRepository extends EntityRepository
{
    // public function findOneBySlug($slug)
    // {
    //     return $this->createQueryBuilder('entity')
    //         ->innerJoin('entity.translations', 'translation')
    //         ->andWhere('translation.slug = :slug')
    //         ->setParameter('slug', $slug)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }

}
