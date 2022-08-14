<?php

namespace App\Repository;

use App\Entity\SearchAction;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method SearchAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchAction[]    findAll()
 * @method SearchAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchActionRepository extends EntityRepository
{
  
}
