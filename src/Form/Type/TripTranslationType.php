<?php

namespace App\Form\Type;

use App\Entity\TripTranslation;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class TripTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('headline', TextType::class,[
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['trip_translation_validation']])
                ]
            ])
            ->add('alternativeHeadline', TextType::class, [
                'required' => false
            ])
            ->add('pushForward', TextType::class, [
                'required' => false
            ])
            ->add('text', CKEditorType::class, [
                'required' => false
            ])
            ->add('textResume', TextAreaType::class, [
                'required' => false
            ])
            ->add('slug', TextType::class, [
                'disabled' => true,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('metaTitle', TextType::class, [
                'required' => false
            ])
            ->add('metaDescription', TextAreaType::class, [
                'required' => false
            ])
        ;
        
        $builder->remove('components');
        $builder->add('components', RichEditorType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TripTranslation::class,
            'validation_groups' => ['trip_translation_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_trip_translation';
    }
}
