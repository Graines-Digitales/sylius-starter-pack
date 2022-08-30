<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ComponentSliderType extends AbstractType
{
    // private $componentService;

    private $slugger;

    public function __construct(
        // Component $componentService
    ){
        // $this->componentService = $componentService;
        $this->slugger = new AsciiSlugger();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_slider'; // code du component enregistré en base de données
        // $templates = $this->componentService->getTemplates($code); // Depends on the code specified on the component creation
        // $styles = $this->componentService->getStyles($code);

        $builder
            ->add('_slug', TextType::class, [
                'disabled' => true,
                'data' => (isset($options['data']['slug']))? $options['data']['slug']: '',
                'label' => 'app.ui_element.field.slug',
                'mapped' => false,
                'help' => 'This field will be automatically edited',
                'required' => false,
            ])
            ->add('slug', HiddenType::class, [
                'disabled' => false,
            ])
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_slider_validation']])
                ],
                'label' => 'app.ui_element.field.title',
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            // ->add('template', ChoiceType::class, [
            //     'choices' => $templates,
            //     'required' => true,
            // ])
            // ->add('style', ChoiceType::class, [
            //     'choices' => $styles,
            //     'required' => true,
            // ])
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
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            $form = $event->getForm();
            if(empty($data['slug'])) {
                $string = $form->getConfig()->getName() . ' ' . $data['title'];
                $data['slug'] = $this->slugger->slug($string)->lower()->toString();
            }
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['component_slider_validation'],
        ]);
    }
}
