<?php

namespace App\Form\Type;

use App\Entity\Trip;
use App\Entity\Category;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityRepository;
use App\Form\Type\AggregateOfferType;
use App\Form\Type\TripTranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isIndexed', CheckboxType::class, [
                'required' => false,
            ])
            ->add('arrivalTime', DateType::class, [
                // 'disabled' => true,
                'widget' => 'single_text',
                // 'with_minutes' => false,
                'required' => false,
                'label' => 'app.ui_element.field.arrival_date',
                'required' => false,
                // 'constraints' => [
                //     new NotBlank(['groups' => ['trip_validation']])
                // ]
            ])
            ->add('departureTime', DateType::class, [
                // 'disabled' => true,
                'widget' => 'single_text',
                // 'with_minutes' => false,
                'required' => false,
                'label' => 'app.ui_element.field.departure_date',
                'required' => false,
                // 'constraints' => [
                //     new NotBlank(['groups' => ['trip_validation']])
                // ]
            ])
            ->add('offers', CollectionType::class, [
                'entry_type' => AggregateOfferType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'required' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'max' => 5,
                        'groups' => ['trip_validation']
                    ])
                ]
            ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.updatedAt', 'DESC')
                    ;
                }
            ])
            ->add('alignPrimaryImage', ChoiceType::class, [
                'choices' => [
                    'top' => 'top',
                    'center' => 'center',
                    'bottom' => 'bottom'
                ],
                'required' => false,
                'placeholder' => 'app.ui_element.field.choose',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class'         => Category::class,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference' => true,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => TripTranslationType::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'validation_groups' => ['trip_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_trip';
    }
}
