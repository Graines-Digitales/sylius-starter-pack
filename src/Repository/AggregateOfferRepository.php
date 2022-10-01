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

    public function create($data = [], $locale = 'fr_FR')
    {
        $entity = new AggregateOffer();
        $entity = $this->hydrate($data, $entity, $locale);

        return $entity;
    }

    public function hydrate($data, $entity, $locale)
    {
        if (isset($data['highPrice'])) {
            $entity->setHighPrice($data['highPrice']);
        }
        if (isset($data['lowPrice'])) {
            $entity->setLowPrice($data['lowPrice']);
        }
        if (isset($data['addOn'])) {
            $entity->setAddOn($data['addOn']);
        }
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (isset($data['price'])) {
            $entity->setPrice($data['price']);
        }
        if (isset($data['priceCurrency'])) {
            $entity->setPriceCurrency($data['priceCurrency']);
        }
        if (isset($data['availabilityEnd'])) {
            $entity->setAvailabilityEnd(new \DateTime($data['availabilityEnd']));
        }
        if (isset($data['availabilityStart'])) {
            $entity->setAvailabilityStart(new \DateTime($data['availabilityStart']));
        }

        return $entity;

    }

}
