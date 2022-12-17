<?php

namespace App\Repository;

use App\Entity\OrderTrip;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method OrderTrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderTrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderTrip[]    findAll()
 * @method OrderTrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderTripRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
