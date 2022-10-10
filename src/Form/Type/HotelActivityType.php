<?php

namespace App\Form\Type;

use App\Entity\HotelActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HotelActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isLocked')
            ->add('isEnabled')
            ->add('isIndexed')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category')
            ->add('tags')
            ->add('primaryImage')
            ->add('secondaryImage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HotelActivity::class,
            'validation_groups' => ['hotel_activity_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_hotel_activity';
    }
}
