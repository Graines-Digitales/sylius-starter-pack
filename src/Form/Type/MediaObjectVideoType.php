<?php

namespace App\Form\Type;

use App\Tools\Media;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('url')
            ->add('encodingFormat', TextType::class,[
                 'data' => 'video/youtube'
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
        return 'app_media_object_icon';
    }
}
