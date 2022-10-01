<?php

namespace App\Form\Type;

use App\Entity\Organization;
use App\Entity\IconMediaObject;
use App\Entity\ImageMediaObject;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Repository\OrganizationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class OrganizationType extends AbstractType
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $builder
            ->add('isEnabled')
            ->add('slug', TextType::class, [
                'disabled' => true,
            ])
            // ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']])
                ]
            ])
            ->add('legalName')
            ->add('phone')
            ->add('mobilePhone')
            ->add('additionalPhone')
            ->add('email')
            // ->add('foundingDate')
            // ->add('numberOfEmployees')
            // ->add('numberOfProjects')
            
            // ->add('isIndexed')
            ->add('description')
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
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
            ])
            ->add('icon', EntityType::class, [
                'attr' => ['class' => 'select2-icon'],
                'class' => IconMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
            ])
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'placeholder' => 'app.ui_element.field.select_category',
            //     'query_builder' => function(CategoryRepository $repo) {
            //         return $repo->createQueryBuilderByTypeOrganization();
            //     }
            // ])
            // ->add('localBusiness')
            // ->add('category')
            // ->add('organization')
            // ->add('parent')
            // ->add('parent', EntityType::class, [
            //     'class' => Organization::class,
            //     'placeholder' => 'app.ui_element.field.select_parent',
            //     'query_builder' => function(OrganizationRepository $repo) use ($configurationProject) {
            //         return $repo->createQueryBuilderByCategoryBrand($configurationProject);
            //     }
            // ])
            ->add('socialLinks', EntityType::class, [
                'class' => Organization::class,
                'expanded'      => true,
                'multiple'      => true,
                // 'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function (OrganizationRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByCategorySocialLink($configurationProject);
                }
            ]);
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
