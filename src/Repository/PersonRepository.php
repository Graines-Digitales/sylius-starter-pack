<?php

namespace App\Repository;

use App\Entity\Person;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
