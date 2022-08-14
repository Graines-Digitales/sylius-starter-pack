<?php

namespace App\Repository;

use App\Entity\MediaObject;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method MediaObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaObject[]    findAll()
 * @method MediaObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaObjectRepository extends EntityRepository
{

    public function createQueryBuilderByEncodingDocument($configurationProject)
    {
        $encodingFormat = $configurationProject['media_encoding_formats']['document'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.encodingFormat in (:encodingFormat)')
            ->setParameter('encodingFormat', $encodingFormat)
            ->orderBy('entity.updatedAt', 'DESC')
        ;
    }

    public function createQueryBuilderByEncodingVideo($configurationProject)
    {
        $encodingFormat = $configurationProject['media_encoding_formats']['video'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.encodingFormat in (:encodingFormat)')
            ->setParameter('encodingFormat', $encodingFormat)
            ->orderBy('entity.updatedAt', 'DESC')
        ;
    }

    public function createQueryBuilderByEncodingSvg($configurationProject)
    {
        $encodingFormat = $configurationProject['media_encoding_formats']['icon'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.encodingFormat in (:encodingFormat)')
            ->setParameter('encodingFormat', $encodingFormat)
            ->orderBy('entity.updatedAt', 'DESC')
        ;
    }

    public function createQueryBuilderByEncodingImage($configurationProject)
    {
        $encodingFormat = $configurationProject['media_encoding_formats']['image'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.encodingFormat in (:encodingFormat)')
            ->setParameter('encodingFormat', $encodingFormat)
            ->orderBy('entity.updatedAt', 'DESC')
        ;
    }

}
