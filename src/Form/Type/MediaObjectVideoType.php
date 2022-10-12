<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\MediaObjectVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectVideoType extends AbstractType
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
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
                'data' => true
            ])
            ->add('name')
            ->add('url', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['media_object_video_validation']])
                ]
            ])
            ->add('_encodingFormat', TextType::class,[
                 'data' => 'video/youtube',
                 'mapped'        => false,
                 'disabled' => true,
                 'label' => 'Encoding format'
                ]
            )
            ->add('encodingFormat', HiddenType::class,[
                'data' => 'video/youtube'
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MediaObjectVideo::class,
            'validation_groups' => ['media_object_video_validation'],
        ]);
    }

     /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_media_object_video';
    }
}
