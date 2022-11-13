<?php

namespace App\Form\Type;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            // ->add('birthday')
            // ->add('placeOfBirth')
            ->add('phone')
            ->add('email')
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
            // ->add('name')
            // ->add('alternateName')
            // ->add('description')
            // ->add('url')
            // ->add('mainEntityOfPage')
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
            // ->add('accommodations')
            // ->add('accommodation')
            // ->add('mediaObjectDocuments')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
