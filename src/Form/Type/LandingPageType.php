<?php

namespace App\Form\Type;

use App\Entity\WebPage;
use App\Entity\Category;
use App\Entity\ImageMediaObject;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\WebPageTranslationType;
use App\Repository\ImageMediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class LandingPageType extends AbstractResourceType
{
    private $container;
    
    private $entityManager;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = 'web-page';
        $category = $this->entityManager->getRepository(Category::class)
            ->findOneBySlug($slug);

        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isIndexed', CheckboxType::class, [
                'required' => false,
            ])
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
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('video', EntityType::class, [
                'required' => false,
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingVideo($configurationProject);
                }
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeWebPage($configurationProject);
                }
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => WebPageTranslationType::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebPage::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_landing_page';
    }
}
