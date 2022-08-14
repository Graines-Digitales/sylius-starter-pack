<?php

namespace App\Form\Type;

use App\Entity\CmsStyle;
use App\Form\Type\WebPageTranslationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;

class CmsStyleType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'app.ui_element.field.name',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.description',
            ])
            ->addEventSubscriber(new AddCodeFormSubscriber())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_cms_style';
    }
}
