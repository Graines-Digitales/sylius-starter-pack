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
        $entity = new HotelTypicalDayElement();
        $entity->setCurrentLocale($locale);
        $entityTranslation = new HotelTypicalDayElementTranslation();
        $entity->addTranslation($entityTranslation); 
        $entity = $this->hydrate($data, $entity, $locale);

        return $entity;
    }

    public function hydrate($data, $entity, $locale)
    {
        $entity->getTranslation()->setLocale($locale);
        $entity->getTranslation()->setTranslatable($entity);

        if (isset($data['label'])) {
            $entity->getTranslation()->setLabel($data['label']);
        }
        if (isset($data['value'])) {
            $entity->getTranslation()->setValue($data['value']);
        }
        if (isset($data['primaryImage'])) {
            // $entity->setAddOn($data['primaryImage']);
        }
        if (isset($data['secondaryImage'])) {
            // $entity->setName($data['secondaryImage']);
        }
        if (isset($data['hours'])) {
            $entity->setHours($data['hours']);
        }
        if (isset($data['labelBgTransparent'])) {
            // $entity->setLabelBgTransparent($data['labelBgTransparent']);
        }
        if (isset($data['description'])) {
            $entity->getTranslation()->setDescription($data['description']);
        }
        if (isset($data['isActive'])) {
            $entity->setIsEnabled($data['isActive']);
        }
        if (isset($data['category'])) {
            if (isset($data['category']['slug'])) {
                $repository = $this->_em->getRepository(Category::class);
                $category = $repository->findOneBySlug($data['category']['slug']);
                $entity->setCategory($category);
            }
        }

        return $entity;

    }

}
