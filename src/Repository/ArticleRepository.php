<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends EntityRepository
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

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    
    public function findByNameField($name)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.name = :val')
            ->setParameter('val', $name)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $id
     * @return Artilces[]
     */
    public function findAllExceptThis($id)
    {
        return $this->createQueryBuilder('pages')
            ->andWhere('articles.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
    
    public function selectByPagination($pagination, $limit = 6)
    {
        $start = ($pagination * $limit) - $limit;

        $queryBuilder = $this->createQueryBuilder('entity')
            ->setMaxResults($limit)
            ->setFirstResult($start);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findMaxPagination($limit = 6)
    {
        $count = $this->createQueryBuilder('entity')
            ->select('count(entity.id)');

        $count = $count->getQuery()
            ->getSingleScalarResult();
        if ($count == 0) {
            return $count;
        }

        $maxPagination = ceil($count / $limit);

        return $maxPagination;
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
