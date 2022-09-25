<?php

namespace App\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method ImageMediaObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageMediaObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageMediaObject[]    findAll()
 * @method ImageMediaObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageMediaObjectRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
