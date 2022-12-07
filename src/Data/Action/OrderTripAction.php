<?php

namespace App\Data\Action;

use App\Entity\OrderTrip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class OrderTripAction
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
                ->getRepository(OrderTrip::class)
                ->find($data['id'])
            ;
        }
        if(null === $entity) {
            $entity = new OrderTrip();
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
       
        // dump($data);die;
        $paymentSplit = ($data['vads_payment_config'] !== 'SINGLE')? true: false;
        $entity->setCustomer($customer);
        $entity->setOrderDate(new \DateTime());
        $entity->setOrderStatus('en cours');
        $entity->setOrderItem($product);
        $entity->setNotes($data['notes']);
        $entity->setDiscount($data['discount']);
        $entity->setDiscountCode($data['discountCode']);
        $entity->setAcceptedOffer($data['acceptedOffer']);
        $entity->setOrderQuantity($data['orderQuantity']);
        $entity->setPaymentSplit($paymentSplit);

        return $entity;
    }

}
