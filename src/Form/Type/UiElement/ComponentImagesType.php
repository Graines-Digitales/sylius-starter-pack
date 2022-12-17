<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\Category;
use App\WebContent\Component;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\MediaObjectImagesTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;



class ComponentImagesType extends AbstractType
{
    private $slugger;

    private $manager;

    private $componentService;

    public function __construct(EntityManagerInterface $manager, Component $componentService)
    {
        $this->slugger = new AsciiSlugger();
        $this->manager = $manager;
        $this->componentService = $componentService;
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
                    new NotBlank(['groups' => ['component_images_validation']])
                ],
                'label' => 'app.ui_element.field.designation',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('images', EntityType::class, [
                'class'         => MediaObjectImage::class,
                'expanded'      => false,
                'multiple'      => true,
                'placeholder' => 'app.ui_element.field.select_option',
                'attr' => ['class' => 'select2-image'],
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
        ;

        $builder
            ->get('images')
            ->addModelTransformer(new MediaObjectImagesTransformer($this->manager))
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
            'validation_groups' => ['component_images_validation'],
        ]);
    }
}
