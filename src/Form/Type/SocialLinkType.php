<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Organization;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;


class SocialLinkType extends AbstractResourceType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $slug = 'reseau-social';
        $category = $this->entityManager->getRepository(Category::class)
            ->findOneBySlug($slug);

        $builder
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('icon', EntityType::class, [
                'attr' => ['class' => 'select2-icon'],
                'required' => false,
                'class' => MediaObjectIcon::class,
                'placeholder' => 'app.ui_element.field.choose'
            ])
            ->add('primaryImage', EntityType::class, [
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObjectImage::class,
                'placeholder' => 'app.ui_element.field.select_primary_image'
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['social_link_validation']])
                ]
            ])
            ->add('url', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['social_link_validation']])
                ]
            ])
            ->add('_category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Category',
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                },
                'disabled' => true
            ])
            ->add('category', EntityType::class, [
                'attr' => ['class' => 'hidden'],
                'class' => Category::class,
                'label' => '',
                'data' => $category,
                'query_builder' => function(CategoryRepository $repo) use ($slug){
                    return $repo->createQueryBuilderBySlug($slug);
                }
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
            'validation_groups' => ['social_link_validation']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_social_link';
    }
}
