<?php

namespace App\Form\Type;

use App\Entity\WebPage;
use App\Entity\Category;
use App\Entity\ImageMediaObject;
use App\WebContent\WebPage as WebContentWebPage;
use App\Repository\CategoryRepository;
use App\Form\Type\WebPageTranslationType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;

class WebPageType extends AbstractResourceType
{
    private $container;

    public function __construct(
        ContainerInterface $container,
        WebContentWebPage $webPageService
    ){
        $this->container = $container;
        $this->webPageService = $webPageService;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = 'web-page';
        $category = $this->webPageService->getCategory($slug);
        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isIndexed', CheckboxType::class, [
                'required' => false,
            ])
            // ->add('createdAt', DateTimeType::class, [
            //     'disabled' => true,
            //     'widget' => 'single_text',
            //     'required' => false,
            // ])
            // ->add('updatedAt', DateTimeType::class, [
            //     'disabled' => true,
            //     'widget' => 'single_text',
            //     'required' => false,
            // ])
            ->add('type', EntityType::class, [
                'class' => Category::class,
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                },
                'disabled' => true
            ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('video', EntityType::class, [
                'required' => false,
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingVideo($configurationProject);
                }
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose',
                // 'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                //     return $repo->createQueryBuilderByTypeWebPage($configurationProject);
                // }
            ])
            // ->add('tags', EntityType::class, [
            //     'class'         => Category::class,
            //     'expanded'      => true,
            //     'multiple'      => true,
            //     'by_reference' => false,
            //     'placeholder' => 'app.ui_element.field.select_option',
            // ])
           
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => WebPageTranslationType::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebPage::class,
            'validation_groups' => ['web_page_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_web_page';
    }
}
