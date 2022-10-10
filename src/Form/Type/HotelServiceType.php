<?php

namespace App\Form\Type;

use App\Entity\HotelService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('withPicto')
            ->add('slugPicto')
            ->add('moreInfo')
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
            'data_class' => HotelService::class,
            'validation_groups' => ['hotel_service_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_hotel_service';
    }
}
