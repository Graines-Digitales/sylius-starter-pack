<?php

namespace App\Form\Type;

use App\Entity\DiscountCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DiscountCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['discount_code_validation']])
                ],
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('expirationDate', DateType::class, [
                'widget' => 'single_text',
                // 'label' => 'app.ui_element.field.arrival_date',
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['discount_code_validation']])
                ],
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'montant' => 'montant',
                    'pourcentage' => 'pourcentage'
                ],
                'constraints' => [
                    new NotBlank(['groups' => ['discount_code_validation']])
                ],
                'required' => true,
                'placeholder' => 'app.ui_element.field.choose',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('value', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['discount_code_validation']])
                ],
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DiscountCode::class,
            'validation_groups' => ['discount_code_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_discount_code';
    }
}
