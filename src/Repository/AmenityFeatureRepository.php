<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\AmenityFeature;
use App\Entity\AmenityFeatureTranslation;
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

    public function create($data = [], $locale = 'fr_FR')
    {
        $entity = new AmenityFeature();
        $entity->setCurrentLocale($locale);
        $entityTranslation = new AmenityFeatureTranslation();
        $entity->addTranslation($entityTranslation); 
        $entity = $this->hydrate($data, $entity, $locale);

        return $entity;
    }

    public function hydrate($data, $entity, $locale)
    {
        $entity->getTranslation()->setLocale($locale);
        $entity->getTranslation()->setTranslatable($entity);

        if (isset($data['name'])) {
            $entity->getTranslation()->setName($data['name']);
        }
        if (isset($data['moreInfo'])) {
            
            $entity->getTranslation()->setDescription($data['moreInfo']);
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
