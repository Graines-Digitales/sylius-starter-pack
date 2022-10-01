<?php

namespace App\Repository;

use App\Entity\ImageMediaObject;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method ImageMediaObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageMediaObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageMediaObject[]    findAll()
 * @method ImageMediaObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageMediaObjectRepository extends EntityRepository
{
<<<<<<< HEAD
    public function create($data = [], $locale = 'fr_FR')
    {
        $entity = new ImageMediaObject();
        // $entity->setCurrentLocale($locale);
        // $entityTranslation = new HotelTypicalDayElementTranslation();
        // $entity->addTranslation($entityTranslation); 
        $entity = $this->hydrate($data, $entity, $locale);

        return $entity;
    }

    public function hydrate($data, $entity, $locale)
    {
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (isset($data['alt'])) {
            $entity->setCaption($data['alt']);
        }
        if (isset($data['description'])) {
            $entity->setDescription($data['description']);
        }
        if (isset($data['filename'])) {
            $entity->setFilename($data['filename']);
        }

        return $entity;
=======
    public function findSortByDate($configurationProject)
    {
        // $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            
            ->orderBy('entity.createdAt', 'DESC')
        ;
>>>>>>> starter-pack
    }
}
