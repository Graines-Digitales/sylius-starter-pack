<?php

namespace App\Repository;

use App\Entity\AmenityFeature;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method AmenityFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmenityFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmenityFeature[]    findAll()
 * @method AmenityFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmenityFeatureRepository extends EntityRepository
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
