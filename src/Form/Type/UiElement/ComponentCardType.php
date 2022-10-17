<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\Category;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\TagsTransformer;
use App\Form\Type\UiElement\ComponentLinkType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CategoryTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\MediaObjectIconTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\MediaObjectImageTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ComponentCardType extends AbstractType
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
                'required' => true,
                'label' => 'app.ui_element.field.designation',
                'constraints' => [
                    new NotBlank(['groups' => ['component_card_validation']])
                ],
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.title',
            ])
            ->add('subtitle', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.subtitle',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'attr' => ['class' => 'select2-standard'],
                'placeholder' => 'app.ui_element.field.select_category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->innerJoin('c.translations', 'translation')
                        ->orderBy('translation.name', 'ASC');
                }
            ])
            ->add('tags', EntityType::class, [
                'class'         => Category::class,
                'expanded'      => false,
                'multiple'      => true,
                'placeholder' => 'app.ui_element.field.select_option',
                'attr' => ['class' => 'select2-standard'],
            ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'attr' => ['class' => 'select2-image'],
                'choice_label' => function ($mediaObject) {
                    return $mediaObject->getFileName();
                }
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.select_primary_image'
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-icon'],
                'class' => MediaObjectIcon::class,
                'placeholder' => 'app.ui_element.field.select_icon',
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
        ;
       
        $builder
            ->get('category')
            ->addModelTransformer(new CategoryTransformer($this->manager))
        ;

        $builder
            ->get('tags')
            ->addModelTransformer(new TagsTransformer($this->manager))
        ;

        $builder
            ->get('primaryImage')
            ->addModelTransformer(new MediaObjectImageTransformer($this->manager))
        ;

        $builder
            ->get('secondaryImage')
            ->addModelTransformer(new MediaObjectImageTransformer($this->manager))
        ;

        $builder
            ->get('icon')
            ->addModelTransformer(new MediaObjectIconTransformer($this->manager))
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
            'validation_groups' => ['component_card_validation'],
        ]);
    }
}
