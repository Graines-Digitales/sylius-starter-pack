<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Component;
use App\Entity\MediaObjectImage;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use App\Form\Type\ComponentTranslationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;

class ComponentType extends AbstractResourceType
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
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'disabled' => true,
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_validation']])
                ]
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
            ->add('category', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeArticle($configurationProject);
                }
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class'         => Category::class,
                'expanded'      => true,
                'multiple'      => true,
                'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => ComponentTranslationType::class,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_component';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Component::class,
            'validation_groups' => ['component_validation'],
        ]);
    }

    
}
