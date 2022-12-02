<?php

namespace App\Form\Type;

use App\Entity\WebPageTranslation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class WebPageTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('headline', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['web_page_translation_validation']])
                ]
            ])
            ->add('alternativeHeadline', TextType::class, [
                'required' => false,
                'help' => 'If empty this field will be automatically edited'
            ])
            ->add('pushForward', TextType::class, [
                'required' => false
            ])
            ->add('text', CKEditorType::class, [
                'required' => false
            ])
            ->add('textResume', TextAreaType::class, [
                'required' => false,
                'help' => 'If empty this field will be automatically edited'
            ])
            ->add('slug', TextType::class, [
                'disabled' => true,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
                
            ])
            ->add('metaTitle', TextType::class, [
                'required' => false,
                'help' => 'If empty this field will be automatically edited'
            ])
            ->add('metaDescription', TextAreaType::class, [
                'required' => false,
                'help' => 'If empty this field will be automatically edited'
            ])
        ;

        $builder->remove('components');
        $builder->add('components', RichEditorType::class, [
            'required' => false,
            'attr_translation_parameters' => [
                'translatable' => false
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebPageTranslation::class,
            'validation_groups' => ['web_page_translation_validation'],
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_web_page_translation';
    }
}
