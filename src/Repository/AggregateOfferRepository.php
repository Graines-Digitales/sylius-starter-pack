<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\AggregateOffer;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method AggregateOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AggregateOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AggregateOffer[]    findAll()
 * @method AggregateOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AggregateOfferRepository extends EntityRepository
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
