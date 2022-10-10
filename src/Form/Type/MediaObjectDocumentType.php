<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObjectDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaObjectDocumentType extends AbstractType
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
                        'groups' => ['media_object_document_validation'],
                        'mimeTypesMessage' => "Formats autorisés : pdf",
                        'maxSize' => "5M",
                        'mimeTypes' => ["application/pdf"]
                    ])
                ]
            ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
                'data' => true
            ])
            ->add('name')
            ->add('encodingFormat',
                null,
                [
                    'disabled' => true,
                    'data' => 'application/pdf',
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
                'expanded'      => true,
                'multiple'      => true,
                'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add(
                'filename',
                null,
                [
                    'disabled' => true,
                    'help' => 'This field will be automatically edited',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaObjectDocument::class,
            'validation_groups' => ['media_object_document_validation']
        ]);
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_media_object_document';
    }
}
