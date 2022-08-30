<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\WebContent\Component;
use App\Form\Type\SearchActionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ComponentSearchActionType extends AbstractType
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
        $code = 'component_search_action'; // Use the code defined on the component creation in admin pannel
        // $templates = $this->componentService->getTemplates($code);
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
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_search_action_validation']])
                ],
                'label' => 'app.ui_element.field.designation',
            ])
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'placeholder' => 'app.ui_element.field.select_category',
            // ])
            ->add('searchAction', SearchActionType::class, [
                'by_reference' => false,
                'label' => '',
                'block_name' => 'entry'
            ])
            // ->add('template', ChoiceType::class, [
            //     'choices' => $templates,
            //     'required' => true,
            // ])
            // ->add('style', ChoiceType::class, [
            //     'choices' => $styles,
            //     'required' => true,
            // ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            $form = $event->getForm();
            if(empty($data['slug'])) {
                $string = $form->getConfig()->getName() . ' ' . $data['designation'];
                $data['slug'] = $this->slugger->slug($string)->lower()->toString();
            }
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['component_search_action_validation'],
        ]);
    }
}
