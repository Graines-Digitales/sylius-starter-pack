<?php

namespace App\Form\Type;

use App\Entity\CmsComponent;
use App\Form\Type\CmsStyleType;
use App\Form\Type\CmsTemplateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;

class CmsComponentType extends AbstractResourceType
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
            ->add('templates', CollectionType::class, [
                'entry_type' => CmsTemplateType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->add('styles', CollectionType::class, [
                'entry_type' => CmsStyleType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_cms_component';
    }
}
