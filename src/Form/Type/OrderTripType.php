<?php

namespace App\Form\Type;

use App\Entity\OrderTrip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OrderTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('confirmationNumber')
            ->add('orderDate')
            ->add('orderNumber')
            ->add('orderStatus')
            ->add('acceptedOffer')
            ->add('notes')
            ->add('discount')
            ->add('discountCode')
            ->add('paymentSplit')
            ->add('discountCode')
            ->add('orderQuantity')
            ->add('orderItem')
            // ->add('acceptedOffer')
            ->add('createdAt', DateTimeType::class, [
                'disabled' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('updatedAt', DateTimeType::class, [
                'disabled' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderTrip::class,
        ]);
    }
}
