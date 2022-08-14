<?php

namespace App\Form\Type;

use App\Tools\Media;
use App\Entity\Category;
use App\Entity\MediaObject;
use App\WebContent\WebPage;
use App\Entity\Organization;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\DataTransformer\MediaObjectTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;

class SocialLinkType extends AbstractResourceType
{
    private $container;

    private $webPageService;

    private $mediaService;

    public function __construct(
        ContainerInterface $container,
        WebPage $webPageService,
        Media $mediaService
    ){
        $this->container = $container;
        $this->webPageService = $webPageService;
        $this->mediaService = $mediaService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = $configurationProject['categories']['social_link']['slug'];
        $category = $this->webPageService->getCategory($slug);
        $icons = $this->mediaService->getIcons();

        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            // ->add('icon', ChoiceType::class, [
            //     'choices' => $icons,
            //     'placeholder' => 'app.ui_element.field.select_icon',
            //     'required' => false,
            // ])
            ->add('iconMedia', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_icon',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingSvg($configurationProject);
                }
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('url', TextType::class, [
                'required' => true
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                }
                // 'disabled' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
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
