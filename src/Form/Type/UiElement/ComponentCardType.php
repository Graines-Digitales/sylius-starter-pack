<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\Category;
use App\Entity\CmsStyle;
use App\Entity\CmsTemplate;
use App\Entity\MediaObject;
use App\WebContent\Component;
use Doctrine\ORM\EntityRepository;
use App\Form\Type\PropertyValueType;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Form\Type\PropertyValueArticleType;
use App\Form\Type\PropertyValueWebPageType;
use App\Form\DataTransformer\TagsTransformer;
use App\Form\DataTransformer\TestTransformer;
use App\Form\Type\UiElement\ComponentLinkType;
use Symfony\Component\Form\CallbackTransformer;
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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentCardType extends AbstractType
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
        $code = 'component_card';
        // $templates = $this->componentService->getTemplates($code); // Depends on the code specified on the component creation
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
                    new NotBlank(['groups' => ['component_card_validation']])
                ],
                'label' => 'app.ui_element.field.title',
            ])
            ->add('subtitle', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.subtitle',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.select_category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->innerJoin('c.translations', 'translation')
                        ->orderBy('translation.name', 'ASC');
                }
            ])
            ->add('tags', EntityType::class, [
                'class'         => Category::class,
                'expanded'      => true,
                'multiple'      => true,
                // 'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                },
                'attr' => ['class' => 'select2-image'],
                'choice_label' => function ($mediaObject) {
                    return $mediaObject->getFileName();
                },
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_icon',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingSvg($configurationProject);
                }
            ])
            // ->add('template', ChoiceType::class, [
            //     'choices' => $templates,
            //     'required' => true,
            // ])
            // ->add('style', ChoiceType::class, [
            //     'choices' => $styles,
            //     'required' => true,
            // ])
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
            ->get('tags')
            ->addModelTransformer(new TagsTransformer($this->manager))
        ;

        $builder
            ->get('primaryImage')
            ->addModelTransformer(new MediaObjectTransformer($this->manager))
        ;

        $builder
            ->get('icon')
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
            'validation_groups' => ['component_card_validation'],
        ]);
    }
}
