<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method MediaObjectDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaObjectDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaObjectDocument[]    findAll()
 * @method MediaObjectDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaObjectDocumentRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
