<?php

namespace App\Form\Type;

use App\Configuration\Project;
use App\Entity\Category;
use App\Entity\ImageMediaObject;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MediaObjectRepository;
use App\Form\Type\CategoryTranslationType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CategoryTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class CategoryType extends AbstractResourceType
{
    private $container;

    private $manager;

    private $configurationService;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $manager,
        Project $configurationService
    ){
        $this->container = $container;
        $this->manager = $manager;
        $this->configurationService = $configurationService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categoryTypeSlugs = $this->configurationService->getCategoryTypeSlugs();

        $configurationProject = $this->container->getParameter('configuration_project');
        $builder
            ->add('isLocked', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => $categoryTypeSlugs,
                'placeholder' => 'app.ui_element.field.choose',
                'required' => false,
            ])
            // ->add('icon', EntityType::class, [
            //     'class' => ImageMediaObject::class,
            //     'placeholder' => 'app.ui_element.field.select_icon',
            //     'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
            //         return $repo->createQueryBuilderByEncodingSvg($configurationProject);
            //     }
            // ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => ImageMediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => CategoryTranslationType::class,
            ])
        ;

         
        // $builder
        //     ->get('type')
        //     ->addModelTransformer(new CategoryTransformer($this->manager));
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
