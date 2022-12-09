<?php

namespace App\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\MediaObjectImageTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;


class SpecialAnnouncementTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('name', TextType::class, [
            //     'required' => true
            // ])
            // ->add('description', TextareaType::class, [
            //     'required' => false
            // ])
            ->add('headline', TextType::class, [
                'required' => false
            ])
            ->add('alternativeHeadline', TextType::class, [
                'required' => false
            ])
            ->add('pushForward', TextType::class, [
                'required' => false
            ])
            ->add('text', WysiwygType::class, [
                'required' => false
            ])
            ->add('textResume', TextAreaType::class, [
                'required' => false
            ])
            ->add('slug', TextType::class, [
                'disabled' => true,
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
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_special_announcement_translation';
    }
}
