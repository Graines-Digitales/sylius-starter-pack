<?php

namespace App\Data\Action;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class OrderAction
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
    
    public function create($data = [], $customer, $product, $locale = 'fr', $persist = true)
    {
        $entity = null;
        if(isset($data['id'])) {
            $entity = $this->entityManager
                ->getRepository(Order::class)
                ->find($data['id'])
            ;
        }
        if(null === $entity) {
            $entity = new Order();
        }
        $entity = $this->hydrate($data, $customer, $product, $entity, $locale);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
   
        return $entity;
    }

    public function hydrate($data = [], $customer, $product, $entity, $locale)
    {
       
        $entity->setCustomer($customer);
        $entity->setOrderDate(new \DateTime());
        $entity->setOrderStatus('en cours');
        $entity->setOrderItem($product);

        return $entity;
    }

}
