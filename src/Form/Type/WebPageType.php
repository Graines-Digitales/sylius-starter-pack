<?php

namespace App\Form\Type;

use App\Entity\WebPage;
use App\Entity\Category;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use App\Entity\MediaObjectVideo;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\WebPageTranslationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;


class WebPageType extends AbstractResourceType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $slug = 'web-page';
        $category = $this->entityManager->getRepository(Category::class)
            ->findOneBySlug($slug);

        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isIndexed', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isLocked', CheckboxType::class, [
                'disabled' => false,
                'required' => false,
            ])
            // ->add('type', EntityType::class, [
            //     'class' => Category::class,
            //     'data' => $category,
            //     'query_builder' => function(CategoryRepository $repo) use ($slug){
            //         return $repo->createQueryBuilderBySlug($slug);
            //     },
            //     'disabled' => true
            // ])
            ->add('primaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('secondaryImage', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-icon'],
                'class' => MediaObjectIcon::class,
                'placeholder' => 'app.ui_element.field.select_icon',
            ])
            ->add('video', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => MediaObjectVideo::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-standard'],
                'class' => Category::class,
                'placeholder' => 'app.ui_element.field.choose',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => WebPageTranslationType::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebPage::class,
            'validation_groups' => ['web_page_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_web_page';
    }
}
