<?php

namespace App\Form\Type;

use App\Entity\Service;
use App\Entity\Category;
use App\Entity\LocalBusiness;
use App\Entity\Product\Product;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LocalBusinessType extends AbstractType
{
    private $container;

    public function __construct(
        ContainerInterface $container
    ){
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configurationProject = $this->container->getParameter('configuration_project');

        $builder
            ->add('slug', TextType::class, [
                'disabled' => true,
            ])
            ->add('isEnabled')
            // ->add('isIndexed')
            ->add('name')
            ->add('description')
            ->add('gmap')
            ->add('organization', OrganizationLocalBusinessType::class, [
                'required' => true
            ])
            ->add('openingHours', CollectionType::class, [
                'entry_type' => OpeningHoursSpecificationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->add('services', EntityType::class, [
                'class'         => Service::class,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('categories', EntityType::class, [
                'class'         => Category::class,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference' => false,
                'label' => 'app.ui_element.field.product_type',
                'placeholder' => 'app.ui_element.field.select_option',
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeProduct($configurationProject);
                }
            ])
            ->add('products', EntityType::class, [
                'class'         => Product::class,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            // ->add('url')
            // ->add('mainEntityOfPage')
            // ->add('createdAt')
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocalBusiness::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_local_busisnes';
    }
}
