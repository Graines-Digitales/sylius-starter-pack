<?php

namespace App\Form\Type;

use App\Entity\PropertyValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PropertyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', TextType::class, [
                'required' => true,
                'label' => 'app.ui_element.field.enter_external_url',
            ])
            // ->add('name', TextType::class, [
            //     'required' => true,
            //     'label' => 'app.ui_element.field.define_external_link_label',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => PropertyValue::class,
        ]);
    }
}
