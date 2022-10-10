<?php

namespace App\Form\Type;

use App\Entity\HotelTypicalDay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelTypicalDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isEnabled')
            ->add('isIndexed')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category')
            ->add('tags')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HotelTypicalDay::class,
            'validation_groups' => ['hotel_typical_day_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_hotel_typical_day';
    }
}
