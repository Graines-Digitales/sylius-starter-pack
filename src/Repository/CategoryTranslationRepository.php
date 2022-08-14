<?php

namespace App\Repository;

use App\Entity\CategoryTranslation;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @method CategoryTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryTranslation[]    findAll()
 * @method CategoryTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryTranslationRepository extends EntityRepository
{

}
