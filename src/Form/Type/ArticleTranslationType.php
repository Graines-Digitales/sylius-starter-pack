<?php

namespace App\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\RichEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;

class ArticleTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('headline', TextType::class, [
                'required' => false
            ])
            ->add('text', WysiwygType::class, [
                'required' => false
            ])
            ->add('textResume', TextAreaType::class, [
                'required' => false
            ])
            ->add('alternativeHeadline', TextType::class, [
                'required' => false
            ])
            ->add('pushForward', TextType::class, [
                'required' => false
            ])
            ->add('articleBody', CKEditorType::class, [
                'required' => false
            ])
            ->add('articleResume', TextareaType::class, [
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
        $builder->remove('component');
        $builder->add('component', RichEditorType::class, [
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_article_translation';
    }
}
