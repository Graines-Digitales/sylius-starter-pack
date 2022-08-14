<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\MediaObject;
use App\Entity\SearchAction;
use App\WebContent\Component;
use App\Form\Type\ArticleType;
use App\Form\Type\CategoryType;
use App\Form\Type\SearchActionType;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use App\Form\Type\MediaObjectImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Form\DataTransformer\ImagesTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentRichSearchActionType extends AbstractType
{
    private $componentService;

    private $manager;

    private $container;

    public function __construct(
        Component $componentService,
        EntityManagerInterface $manager,
        ContainerInterface $container
    ){
        $this->componentService = $componentService;
        $this->manager = $manager;
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_rich_search_action'; // Use the code defined on the component creation in admin pannel
        $templates = $this->componentService->getTemplates($code);
        $styles = $this->componentService->getStyles($code);
        $configurationProject = $this->container->getParameter('configuration_project');

        $builder
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.title',
            ])
            ->add('content', TextAreaType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('images', EntityType::class, [
                'class'         => MediaObject::class,
                'expanded'      => false,
                'multiple'      => true,
                'placeholder' => 'app.ui_element.field.select_option',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            // ->add('images', CollectionType::class, [
            //     // 'entry_type' => MediaObjectImageType::class,
            //     'button_add_label' => 'app.ui_element.form.add_item',
            //     // 'allow_add' => true,
            //     // 'allow_delete' => true,
            //     // 'by_reference' => false,
            //     // 'delete_empty' => true,
            //     'label' => 'monsieurbiz_richeditor_plugin.ui_element.monsieurbiz.image_collection.field.images',
            // ])
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'placeholder' => 'app.ui_element.field.select_category',
            // ])
            ->add('searchAction', SearchActionType::class, [
                'by_reference' => false,
                'label' => '',
                'block_name' => 'entry'
            ])
            ->add('template', ChoiceType::class, [
                'choices' => $templates,
                'required' => true,
            ])
            ->add('style', ChoiceType::class, [
                'choices' => $styles,
                'required' => true,
            ])
        ;

        $builder
            ->get('images')
            ->addModelTransformer(new ImagesTransformer($this->manager))
        ;
    }
}
