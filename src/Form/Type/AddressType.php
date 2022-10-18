<?php

namespace App\Form\Type;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('streetAddress')
            ->add('additionalStreetAddress')
            ->add('postalCode')
            ->add('addressLocality')
            ->add('addressCountry')
            // ->add('phone')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('organizations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'validation_groups' => ['address_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_address';
    }
}
