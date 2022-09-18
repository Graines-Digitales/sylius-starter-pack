<?php

namespace App\Form\Type;


use App\Entity\Category;
use App\Entity\Organization;
use App\Entity\ImageMediaObject;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\OrganizationRepository;
use App\Repository\ImageMediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class OrganizationLocalBusinessType extends AbstractType
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
        $slug = 'local-business';
        $category = $this->entityManager->getRepository(Category::class)
            ->findOneBySlug($slug);
        
        $builder
            ->add('name', TextType::class, [
                'required' => false
            ])
            ->add('legalName')
            ->add('phone')
            ->add('email')
            ->add('mobilePhone')
            ->add('fax')
            ->add('url')
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('secondaryImage', EntityType::class, [
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_secondary_image',
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeLocalBusiness($configurationProject);
                }
                // 'disabled' => true
            ])
            ->add('parent', EntityType::class, [
                'class' => Organization::class,
                'placeholder' => 'app.ui_element.field.select_parent',
                'query_builder' => function(OrganizationRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByCategoryBrand($configurationProject);
                }
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
        return 'app_organization_local_business';
    }
}
