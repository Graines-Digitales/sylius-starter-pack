<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\LocalBusiness;
use App\Entity\ImageMediaObject;
use App\Form\Type\CategoryTranslationType;
use App\Repository\ImageMediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class CategoryTypeProductType extends AbstractResourceType
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
            ->add('primaryImage', EntityType::class, [
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(ImageMediaObjectRepository $repo) use ($configurationProject){
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
