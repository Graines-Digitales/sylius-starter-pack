<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\CmsStyle;
use App\Entity\CmsTemplate;
use Doctrine\ORM\EntityRepository;
use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentSliderType extends AbstractType
{
    private $componentService;

    public function __construct(Component $componentService)
    {
        $this->componentService = $componentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_slider'; // code du component enregistré en base de données
        $templates = $this->componentService->getTemplates($code); // Depends on the code specified on the component creation
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
            ->add('cards', CollectionType::class, [
                'entry_type' => ComponentCardType::class,
                'button_add_label' => 'app.ui_element.form.add_item',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'label' => 'app.ui_element.form.item_list',
                'label_attr' => ['class' => 'collection-widget']
            ])
        ;
    }
}
