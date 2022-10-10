<?php

namespace App\Repository;

use App\Entity\MediaObjectImage;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method MediaObjectImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaObjectImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaObjectImage[]    findAll()
 * @method MediaObjectImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaObjectImageRepository extends EntityRepository
{
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
    }
}
