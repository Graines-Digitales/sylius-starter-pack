<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\Category;
use App\Form\Type\ParamsType;
use App\WebContent\Component;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use App\Entity\MediaObjectVideo;
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
use App\Form\DataTransformer\MediaObjectIconTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\MediaObjectImageTransformer;
use App\Form\DataTransformer\MediaObjectVideoTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ComponentCardsType extends AbstractType
{
    private $slugger;

    private $entityManager;

    private $componentService;

    public function __construct(EntityManagerInterface $entityManager, Component $componentService)
    {
        $this->slugger = new AsciiSlugger();
        $this->entityManager = $entityManager;
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
                    new NotBlank(['groups' => ['component_cards_validation']])
                ],
                'label' => 'app.ui_element.field.designation',
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.title',
            ])
            ->add('subtitle', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.subtitle',
            ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.choose',
                'attr' => ['class' => 'select2-image'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.updatedAt', 'DESC')
                    ;
                },
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.updatedAt', 'DESC')
                    ;
                },
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-icon'],
                'class' => MediaObjectIcon::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.updatedAt', 'DESC')
                    ;
                },
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('video', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => MediaObjectVideo::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.updatedAt', 'DESC')
                    ;
                },
                'attr_translation_parameters' => [
                    'translatable' => false
                ]
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'attr' => ['class' => 'select2-standard'],
            //     'placeholder' => 'app.ui_element.field.choose',
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('c')
            //         ->innerJoin('c.translations', 'translation')
            //             ->orderBy('translation.name', 'ASC');
            //     }
            // ])
            // ->add('tags', EntityType::class, [
            //     'class'         => Category::class,
            //     'expanded'      => false,
            //     'multiple'      => true,
            //     'placeholder' => 'app.ui_element.field.select_option',
            //     'attr' => ['class' => 'select2-standard'],
            // ])
            // ->add('view', ChoiceType::class, [
            //     'choices' => $views,
            //     'attr' => ['class' => 'select2-standard'],
            //     'required' => true,
            //     'placeholder' => 'app.ui_element.field.choose',
            // ])
            // ->add('params', CollectionType::class, [
            //     'entry_type' => ParamsType::class,
            //     'button_add_label' => 'app.ui_element.form.add_params',
            //     'attr' => [
            //         'data-type' => 'sub_accordion'
            //     ],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'delete_empty' => true,
            //     'label' => 'app.ui_element.field.params_collection.default',
            // ])
            ->add('cards', CollectionType::class, [
                'entry_type' => ComponentCardType::class,
                'button_add_label' => 'app.ui_element.form.add_item',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
                'label' => 'app.ui_element.field.card_collection.default',
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
            ->get('primaryImage')
            ->addModelTransformer(new MediaObjectImageTransformer($this->entityManager))
        ;
        
        $builder
            ->get('icon')
            ->addModelTransformer(new MediaObjectIconTransformer($this->entityManager))
        ;

        $builder
            ->get('video')
            ->addModelTransformer(new MediaObjectVideoTransformer($this->entityManager))
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
            'validation_groups' => ['component_cards_validation'],
        ]);
    }
}
