<?php

namespace App\Repository;

use App\Entity\Order;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
