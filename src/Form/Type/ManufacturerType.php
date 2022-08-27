<?php

namespace App\Form\Type;

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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;

class ManufacturerType extends AbstractResourceType
{
    private $container;

    private $webPageService;

    public function __construct(ContainerInterface $container, WebPage $webPageService)
    {
        $this->container = $container;
        $this->webPageService = $webPageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = 'manufacturer';
        $category = $this->webPageService->getCategory($slug);
        
        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
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
           
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                }
                // 'disabled' => true
            ])
            ->add('url')
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
