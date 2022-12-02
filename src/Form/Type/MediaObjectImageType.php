<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObjectImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
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
            ->add('file', null, [
                'constraints' => [
                    new File([
                        'groups' => ['media_object_image_validation'],
                        'mimeTypesMessage' => "Formats autorisés : png,jpeg,gif",
                        'maxSize' => "5M",
                        'mimeTypes' => ["image/png","image/jpeg","image/jpg","image/gif",]
                    ])
                ]
            ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
                'data' => true
            ])
            ->add('name', null, [
                'help' => 'If empty this field will be automatically edited'
            ])
            ->add('caption', null, [ 
                    'label' => 'Alt balise',
                    'help' => 'If empty this field will be automatically edited'
                ]
            )
            ->add('encodingFormat',
                null,
                [
                    'disabled' => true,
                    // 'help' => 'This field will be automatically edited',
                ]
            )
            ->add('category', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class'         => Category::class,
                'expanded'      => false,
                'multiple'      => true,
                'by_reference' => true,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add(
                'filename',
                null,
                [
                    'disabled' => true,
                    'help' => 'If empty this field will be automatically edited'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaObjectImage::class,
            'validation_groups' => ['media_object_image_validation']
        ]);
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_media_object_image';
    }
}
