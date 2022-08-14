<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentPageHeaderType extends AbstractType
{
    private $componentService;

    public function __construct(Component $componentService)
    {
        $this->componentService = $componentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_page_header'; // Use the code defined on the component creation in admin pannel
        $templates = $this->componentService->getTemplates($code);
        $styles = $this->componentService->getStyles($code);

        $builder
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.title',
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
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
