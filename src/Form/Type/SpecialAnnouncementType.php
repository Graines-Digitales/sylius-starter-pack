<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObject;
use App\Entity\SpecialAnnouncement;
use App\Repository\CategoryRepository;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Type\SpecialAnnouncementTranslationType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;


class SpecialAnnouncementType extends AbstractResourceType
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
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => SpecialAnnouncementTranslationType::class,
            ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isIndexed', CheckboxType::class, [
                'required' => false,
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                // 'placeholder' => 'app.ui_element.field.select_category',
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeSpecialAnnouncement($configurationProject);
                }
            ])
            // ->add('tags', EntityType::class, [
            //     'class'         => Category::class,
            //     'expanded'      => false,
            //     'multiple'      => true,
            //     'placeholder' => 'app.ui_element.field.select_option',
            // ])
            // ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('createdAt', DateTimeType::class, [
                'disabled' => true,
                'widget' => 'single_text'
            ])
            ->add('updatedAt', DateTimeType::class, [
                'disabled' => true,
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpecialAnnouncement::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_special_announcement';
    }
}
