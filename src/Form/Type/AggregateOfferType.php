<?php

namespace App\Form\Type;

use App\Entity\AggregateOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AggregateOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('highPrice')
            // ->add('lowPrice')
            ->add('offerCount')
            // ->add('addOn')
            ->add('name')
            ->add('price')
            // ->add('priceCurrency')
            // ->add('availabilityEnd')
            // ->add('availabilityStart')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('rooms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AggregateOffer::class,
        ]);
    }
}
