<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\WebContent\Component;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ComponentIframeType extends AbstractType
{
    private $componentService;

    public function __construct(Component $componentService)
    {
        $this->componentService = $componentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_iframe'; // Use the code defined on the component creation in admin pannel
        $templates = $this->componentService->getTemplates($code);
        $styles = $this->componentService->getStyles($code);

        $builder
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('link', TextType::class, [
                'label' => 'app.ui_element.iframe.link',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('template', ChoiceType::class, [
                'choices' => $templates,
                'required' => true,
            ])
            ->add('style', ChoiceType::class, [
                'choices' => $styles,
                'required' => true,
            ])
        ;
    }
}
