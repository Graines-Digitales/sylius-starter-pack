<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObjectIcon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectIconType extends AbstractType
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
                        'groups' => ['media_object_icon_validation'],
                        'mimeTypesMessage' => "Formats autorisés : svg",
                        'maxSize' => "5M",
                        'mimeTypes' => ["image/svg+xml"]
                    ])
                ]
            ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
                'data' => true
            ])
            ->add('name')
            ->add('encodingFormat', TextType::class,[
                    'data' => 'image/svg+xml',
                    'disabled' => true,
                    'required' => false
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
            'data_class' => MediaObjectIcon::class,
            'validation_groups' => ['media_object_icon_validation']
        ]);
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_media_object_svg';
    }
}
