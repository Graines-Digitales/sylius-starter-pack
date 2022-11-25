<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ComponentBlockquoteType extends AbstractType
{
    private $manager;
    
    private $container;

    private $slugger;

    private $componentService;

    public function __construct(
        EntityManagerInterface $manager,
        ContainerInterface $container,
        Component $componentService
    ){
        $this->manager = $manager;
        $this->container = $container;
        $this->componentService = $componentService;
        $this->slugger = new AsciiSlugger();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $views = $this->componentService->getComponentViews();
        
        $builder
            ->add('_slug', TextType::class, [
                'disabled' => true,
                'data' => (isset($options['data']['slug']))? $options['data']['slug']: '',
                'label' => 'app.ui_element.field.slug',
                'mapped' => false,
                'help' => 'This field will be automatically edited',
                'required' => false,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('slug', HiddenType::class, [
                'disabled' => false,
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('designation', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_blockquote_validation']])
                ],
                'label' => 'app.ui_element.field.designation',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
          
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            $form = $event->getForm();
            if(empty($data['slug'])) {
                $string = $form->getConfig()->getName() . ' ' . $data['designation'];
                $data['slug'] = $this->slugger->slug($string)->lower()->toString();
            }
            unset($data['_slug']);
            $event->setData($data);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['component_blockquote_validation'],
        ]);
    }
}
