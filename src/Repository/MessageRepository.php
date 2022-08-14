<?php

namespace App\Repository;

use App\Entity\Message;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;


/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends EntityRepository
{

    public function findByTypeContact($configurationProject)
    {
        $slug = $configurationProject['forms']['contact_default']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.origin = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('entity.dateSent', 'DESC')
        ;
    }

    public function findByTypeQuote($configurationProject)
    {
        $slug = $configurationProject['forms']['contact_quote']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.origin = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('entity.dateSent', 'DESC')
        ;
    }

    public function findByTypeAppointment($configurationProject)
    {
        $slug = $configurationProject['forms']['contact_appointment']['slug'];

        return $this->createQueryBuilder('entity')
            ->andWhere('entity.origin = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('entity.dateSent', 'DESC')
        ;
    }

    public function findByTypeSpecialAnnouncement($configurationProject)
    {
        $slugs = [
            $configurationProject['forms']['contact_special_announcement']['slug'],
            $configurationProject['forms']['contact_special_announcement_free']['slug']
        ];
        
        return $this->createQueryBuilder('entity')
            ->andWhere('entity.origin IN(:slugs)')
            ->setParameter('slugs', $slugs)
            ->orderBy('entity.dateSent', 'DESC')
        ;
    }

}
