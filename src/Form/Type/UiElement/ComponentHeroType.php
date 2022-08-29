<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\ImageMediaObject;
use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Form\DataTransformer\MediaObjectTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentHeroType extends AbstractType
{
    private $componentService;

    private $manager;
    
    private $container;

    private $slugger;

    public function __construct(
        Component $componentService,
        EntityManagerInterface $manager,
        ContainerInterface $container
    ){
        $this->componentService = $componentService;
        $this->manager = $manager;
        $this->container = $container;
        $this->slugger = new AsciiSlugger();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_hero'; // Use the code defined on the component creation in admin pannel
        // $templates = $this->componentService->getTemplates($code);
        // $styles = $this->componentService->getStyles($code);
        $configurationProject = $this->container->getParameter('configuration_project');

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
                    new NotBlank(['groups' => ['component_hero_validation']])
                ],
                'label' => 'app.ui_element.field.title',
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image'
            ])
            ->add('label', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.label',
            ])
            ->add('link', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.link',
                'constraints' => [
                    new Assert\Url([]),
                ],
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
        
        $builder
            ->get('primaryImage')
            ->addModelTransformer(new MediaObjectTransformer($this->manager))
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
            'validation_groups' => ['component_hero_validation'],
        ]);
    }
}
