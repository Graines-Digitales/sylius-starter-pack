<?php

namespace App\Repository;

use App\Entity\WebPage;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method WebPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebPage[]    findAll()
 * @method WebPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebPageRepository extends EntityRepository
{
    // public function __construct(EntityManagerInterface $registry)
    // {
    //     parent::__construct($registry, $registry->getClassMetadata(WebPage::class));
    // }

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

    public function finAllArrayResult()
    {
        $results = $this->createQueryBuilder('entity')
            ->innerJoin('entity.translations', 'translation')
            ->select("entity, translation")
            ->getQuery()
            ->getResult()   
        ;
        
        $data = [];
        foreach($results as $result) {
            $data[$result->getSlug()] = $result;
        }

        return $data;
    }

    public function createQueryBuilderByTypeWebPage($configurationProject)
    {
        $type = 'web-page';

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

    public function createQueryBuilderByTypeLandingPage($configurationProject)
    {
        $type = 'landing-page';

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.type in (:type)')
            ->setParameter('type', $type)
        ;
    }

/*     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebPage::class);
    } */

    // /**
    //  * @return WebPage[] Returns an array of WebPage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebPage
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
