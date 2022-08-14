<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends EntityRepository
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

    public function createQueryBuilderBySlug($slug)
    {
        return $this->createQueryBuilder('entity')
            ->innerJoin('entity.translations', 'translation')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function createQueryBuilderByTypeOrganization()
    {
        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', 'organization')
        ;
    }

    public function createQueryBuilderByType()
    {
        dump('ici');
        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', 'organization')
        ;
    }

    public function createQueryBuilderByTypeLocalBusiness($configurationProject)
    {
        $type = $configurationProject['categories']['local_business']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

    public function createQueryBuilderByTypeSpecialAnnouncement($configurationProject)
    {
        $type = $configurationProject['categories']['special_announcement']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

    public function createQueryBuilderByTypeArticle($configurationProject)
    {
        $type = $configurationProject['categories']['article']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

    public function createQueryBuilderByTypeWebPage($configurationProject)
    {
        $type = $configurationProject['categories']['web_page']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

    public function createQueryBuilderByTypeProduct($configurationProject)
    {
        $type = $configurationProject['categories']['product']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }
}
