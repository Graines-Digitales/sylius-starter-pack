<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObject;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaObjectImageType extends AbstractType
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
            ->add('file')
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
                'data' => true
            ])
            ->add('name')
            ->add('alt', null, [ 'label' => 'SEO ALT Balise' ])
            ->add(
                'filename',
                null,
                [
                    'disabled' => true,
                    'help' => 'If empty, the file name will be generated automatically',
                ]
            )
            ->add('encodingFormat',
                null,
                [
                    'disabled' => true,
                    'help' => 'This field will be automatically edited',
                ]
            )
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.select_category',
                'query_builder' => function(CategoryRepository $repo) use ($configurationProject) {
                    return $repo->createQueryBuilderByTypeArticle($configurationProject);
                }
            ])
            ->add('tags', EntityType::class, [
                'class'         => Category::class,
                'expanded'      => true,
                'multiple'      => true,
                'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaObject::class,
        ]);
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_media_object';
    }
}
