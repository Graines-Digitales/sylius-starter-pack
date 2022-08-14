<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObject;
use App\WebContent\WebPage;
use App\Entity\LocalBusiness;
use App\Configuration\Project;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MediaObjectRepository;
use App\Form\Type\CategoryTranslationType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CategoryTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class CategoryTypeProductType extends AbstractResourceType
{
    private $container;

    private $manager;

    private $configurationService;
    
    private $webPageService;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $manager,
        Project $configurationService,
        WebPage $webPageService
    ){
        $this->container = $container;
        $this->manager = $manager;
        $this->configurationService = $configurationService;
        $this->webPageService = $webPageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $categoryTypeSlugs =  $this->configurationService->getCategoryTypeSlugs($configurationProject['categories']['product']['slug']);

        // $slug = $configurationProject['categories']['product']['slug'];
        $category = $configurationProject['categories']['product']['slug'];// $this->webPageService->getCategory($slug);
        $builder
            // ->add('isLocked', CheckboxType::class, [
            //     'required' => false,
            // ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $categoryTypeSlugs,
                'placeholder' => 'app.ui_element.field.select_type',
                'required' => false,
                'data' => $category,
            ])
            // ->add('type', EntityType::class, [
            //     'class' => Category::class,
            //     'data' => $category,
            //     'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
            //         return $repo->createQueryBuilderByTypeProduct($configurationProject);
            //     }
            //     // 'disabled' => true
            // ])
            ->add('icon', EntityType::class, [
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
            ->add('localBusinesses', EntityType::class, [
                'class'         => LocalBusiness::class,
                'expanded'      => false,
                'multiple'      => true,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => CategoryTranslationType::class,
            ])
        ;

         
        // $builder
        //     ->get('type')
        //     ->addModelTransformer(new CategoryTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_category';
    }
}
