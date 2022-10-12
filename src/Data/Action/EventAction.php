<?php

namespace App\Data\Action;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class EventAction
{
    private $slugger;

    private $entityManager;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }
    
    public function create($data = [], $locale = 'fr', $persist = true)
    {
        $entity = new Event();
        $entity = $this->hydrate($data, $entity);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function hydrate($data = [], $entity)
    {

        if(isset($data['beginAt'])) {
            $beginAt = new \DateTime($data['beginAt']);
            $entity->setBeginAt($beginAt);
        }

        if(isset($data['endAt'])) {
            $endAt = new \DateTime($data['endAt']);
            $entity->setEndAt($endAt);
        }

        if(isset($data['respondAt'])) {
            $respondAt = new \DateTime($data['respondAt']);
            $entity->setRespondAt($respondAt);
        }

        if(isset($data['person'])) {
           dump('personnn');die;
        }

        if(isset($data['comment'])) {
            $entity->setComment($data['comment']);
        }

        if(isset($data['details'])) {
            $entity->setDetails($data['details']);
        }

        if(isset($data['name'])) {
            $entity->setName($data['name']);
        }

        if(isset($data['description'])) {
            $entity->setDescription($data['description']);
        }

        return $entity;
    }

}
