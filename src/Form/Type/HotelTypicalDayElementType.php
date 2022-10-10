<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HotelTypicalDayElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('label')
           ->add('value')
           ->add('category')
           ->add('isActive')
           ->add('primaryImage')
           ->add('secondaryImage')
           ->add('description')
           ->add('hours')
           ->add('labelBgTransparent')
           ->add('event')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\HotelTypicalDayElement',
            'allow_extra_fields' => true,
            'validation_groups' => ['hotel_typical_day_element_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_hotel_typical_day_element';
    }
}
