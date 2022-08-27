<?php

namespace App\Form\Type;

use App\Entity\Article;
use App\Entity\CmsLink;
use App\Entity\WebPage;
use App\Entity\MediaObject;
use App\WebContent\Component;
use App\Form\Type\CmsLinkTranslationType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;

class CmsLinkType extends AbstractResourceType
{
    private $componentService;
    private $container;

    public function __construct(
        ContainerInterface $container,
        Component $componentService
    ){
        $this->container = $container;
        $this->componentService = $componentService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $icons = $this->componentService->getIcons(null);
        $configurationProject = $this->container->getParameter('configuration_project');
        $builder
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => CmsLinkTranslationType::class,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'attr' => ['class' => 'select2-image'],
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.choose',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingSvg($configurationProject);
                }
            ])
            ->add('url', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.external_url',
                'constraints' => [
                    new Assert\Url([]),
                ], 
            ])
            ->add('webPage', EntityType::class, [
                'class' => WebPage::class,
                'required' => false,
                'placeholder' => 'app.ui_element.field.choose',
            ])
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'required' => false,
                'placeholder' => 'app.ui_element.field.choose',
            ])
            // ->add('cmsLinks', CollectionType::class, [
            //     'entry_type' => CmsLinkType::class,
            //     'label' => 'app.ui_element.field.cms_links',
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'delete_empty' => true,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CmsLink::class,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_cms_link';
    }
}
