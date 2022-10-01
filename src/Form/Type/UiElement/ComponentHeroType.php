<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\ImageMediaObject;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Form\Type\UiElement\ComponentLinkType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\ImageMediaObjectTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentHeroType extends AbstractType
{
    private $manager;
    
    private $container;

    private $slugger;

    public function __construct(
        EntityManagerInterface $manager,
        ContainerInterface $container
    ){
        $this->manager = $manager;
        $this->container = $container;
        $this->slugger = new AsciiSlugger();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('links', CollectionType::class, [
                'entry_type' => ComponentLinkType::class,
                'button_add_label' => 'app.ui_element.form.add_item',
                'attr' => [
                    'data-type' => 'sub_accordion'
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'label' => 'app.ui_element.field.link_collection.default',
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
            ->addModelTransformer(new ImageMediaObjectTransformer($this->manager))
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
