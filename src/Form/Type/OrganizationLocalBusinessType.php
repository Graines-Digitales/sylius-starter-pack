<?php

namespace App\Form\Type;


use App\Entity\Category;
use App\Entity\MediaObject;
use App\WebContent\WebPage;
use App\Entity\Organization;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Repository\OrganizationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrganizationLocalBusinessType extends AbstractType
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
        $slug = $configurationProject['categories']['local_business']['slug'];
        $category = $this->webPageService->getCategory($slug);
        
        $builder
            // ->add('isEnabled')
            // ->add('slug')
            ->add('name', TextType::class, [
                'required' => false
            ])
            ->add('legalName')
            ->add('phone')
            ->add('email')
            // ->add('foundingDate')
            // ->add('numberOfEmployees')
            // ->add('numberOfProjects')
            ->add('mobilePhone')
            ->add('fax')
            // ->add('isIndexed')
            // ->add('description')
            ->add('url')
            // ->add('mainEntityOfPage')
            // ->add('createdAt')
            // ->add('updatedAt')
            
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('secondaryImage', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_secondary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            // ->add('localBusiness')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeLocalBusiness($configurationProject);
                }
                // 'disabled' => true
            ])
            // ->add('organization')
            ->add('parent', EntityType::class, [
                'class' => Organization::class,
                'placeholder' => 'app.ui_element.field.select_parent',
                'query_builder' => function(OrganizationRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByCategoryBrand($configurationProject);
                }
            ])
            // ->add('socialLinks', EntityType::class, [
            //     'class' => Organization::class,
            //     'placeholder' => 'app.ui_element.field.select_primary_image',
            //     'query_builder' => function(OrganizationRepository $repo) use ($configurationProject){
            //         return $repo->createQueryBuilderByCategorySocialLink($configurationProject);
            //     },
            //     'multiple' => true
            // ])
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
        return 'app_organization';
    }
}
