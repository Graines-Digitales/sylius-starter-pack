<?php

namespace App\Repository;

use App\Entity\CmsTemplate;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CmsTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsTemplate[]    findAll()
 * @method CmsTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsTemplateRepository extends EntityRepository
{

    // /**
    //  * @return CmsTemplate[] Returns an array of CmsTemplate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsTemplate
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
