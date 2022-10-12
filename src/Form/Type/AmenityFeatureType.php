<?php

namespace App\Form\Type;

use App\Entity\AmenityFeature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmenityFeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('withPicto')
            ->add('slugPicto')
            ->add('name')
            ->add('alternateName')
            ->add('description')
            ->add('url')
            ->add('mainEntityOfPage')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('accommodations')
            ->add('rooms')
            ->add('category')
            ->add('tags')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AmenityFeature::class,
            'validation_groups' => ['amenity_feature_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_amenity_feature';
    }
}
