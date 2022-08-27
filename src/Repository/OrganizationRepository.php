<?php

namespace App\Repository;

use App\Entity\Organization;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends EntityRepository
{
   

    // public function findAll()
    // {
    //     dump('allo');die;
    //     return $this->createQueryBuilder('o')
    //         // ->andWhere('l.exampleField = :val')
    //         // ->setParameter('val', $value)
    //         // ->orderBy('o.id', 'ASC')
    //         // ->setMaxResults(1)
    //         // ->getQuery()
    //         // ->getResult()
    //     ;
    // }

    public function createQueryBuilderByCategoryManufacturer($configurationProject)
    {
        $slug = 'manufacturer';

        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.category', 'category')
            ->innerJoin('category.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function findByCategoryManufacturer($configurationProject)
    {
        return $this->createQueryBuilderByCategoryManufacturer($configurationProject)
            ->getQuery()
            ->getResult()
        ;
    }

    public function createQueryBuilderByCategoryLocalBusiness($configurationProject)
    {
        $slug = 'local-business';

        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.category', 'category')
            ->innerJoin('category.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }
    
    public function createQueryBuilderByCategorySocialLink($configurationProject)
    {
        $slug = 'reseau-social';

        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.category', 'category')
            ->innerJoin('category.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function findByCategoryBrand($configurationProject)
    {
        return $this->createQueryBuilderByCategoryBrand($configurationProject)
            ->getQuery()
            ->getResult()
        ;
    }

    public function createQueryBuilderByCategoryBrand($configurationProject)
    {
        $slug = 'brand';

        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.category', 'category')
            ->innerJoin('category.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }


}
