<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Form\Type\PropertyValueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\Type\PropertyValueWebPageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ComponentLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_link_validation']])
                ],
                'label' => 'app.ui_element.field.designation',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('label', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.label',
            ])
            ->add('web_page_link', PropertyValueWebPageType::class, [
                'by_reference' => false,
                'label' => 'app.ui_element.field.link_to_webpage',
                'block_name' => 'entry',
                'required' => false,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('external_link', PropertyValueType::class, [
                'by_reference' => false,
                'label' => 'app.ui_element.field.link_to_external_link',
                'block_name' => 'entry',
                'required' => false,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
                // 'label' => 'app.ui_element.field.link',
                // 'constraints' => [
                //     new Assert\Url([]),
                // ],
            ])
        ;

    }
}
