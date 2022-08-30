<?php

namespace App\Form\Type;

use App\Entity\CmsMenu;
use App\Entity\ImageMediaObject;
use App\Form\Type\CmsLinkType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;

class CmsMenuType extends AbstractResourceType
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
            ->add('isEnabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']])
                ]
            ])
            // ->add('description')
            // ->addEventSubscriber(new AddCodeFormSubscriber())
            // ->add('menus', CollectionType::class, [
            //     'entry_type' => CmsMenuType::class,
            //     'label' => 'app.ui_element.field.cms_menus',
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'delete_empty' => true,
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
            ->add('menus', EntityType::class, [
                'class' => CmsMenu::class,
                'placeholder' => 'app.ui_element.field.select_submenu',
                // 'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ])
            ->add('cmsLinks', CollectionType::class, [
                'entry_type' => CmsLinkType::class,
                'label' => 'app.ui_element.field.cms_links',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CmsMenu::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_cms_menu';
    }
}
