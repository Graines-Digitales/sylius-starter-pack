<?php

namespace App\Form\Type;

use App\Tools\Media;
use App\Entity\Category;
use App\WebContent\WebPage;
use App\Entity\Organization;
use App\Entity\IconMediaObject;
use App\Entity\ImageMediaObject;
use App\Repository\CategoryRepository;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;


class SocialLinkType extends AbstractResourceType
{
    private $container;

    private $webPageService;

    // private $mediaService;

    public function __construct(
        ContainerInterface $container,
        WebPage $webPageService
        // Media $mediaService
    ){
        $this->container = $container;
        $this->webPageService = $webPageService;
        // $this->mediaService = $mediaService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = 'reseau-social';
        $category = $this->webPageService->getCategory($slug);
        // $icons = $this->mediaService->getIcons();

        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('icon', EntityType::class, [
                'attr' => ['class' => 'select2-image'],
                'required' => false,
                // 'constraints' => [
                //     new NotBlank(['groups' => ['social_link_validation']])
                // ],
                'class' => IconMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                // 'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                //     return $repo->createQueryBuilderByEncodingSvg($configurationProject);
                // }
            ])
            ->add('primaryImage', EntityType::class, [
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                // 'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                //     return $repo->createQueryBuilderByEncodingImage($configurationProject);
                // }
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['social_link_validation']])
                ]
            ])
            ->add('url', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['social_link_validation']])
                ]
            ])
            ->add('_category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Category',
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                },
                'disabled' => true
            ])
            ->add('category', EntityType::class, [
                'attr' => ['class' => 'hidden'],
                'class' => Category::class,
                'label' => '',
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                }
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
            'validation_groups' => ['social_link_validation']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_manufacturer';
    }
}
