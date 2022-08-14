<?php

namespace App\Form\Type;

use App\Entity\Service;
use App\Entity\MediaObject;
use App\Entity\LocalBusiness;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceType extends AbstractType
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
            ->add('slug', TextType::class, [
                'disabled' => true,
            ])
            ->add('isEnabled')
            ->add('name')
            ->add('icon', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_icon',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingSvg($configurationProject);
                }
            ])
            ->add('localBusinesses', EntityType::class, [
                'class'         => LocalBusiness::class,
                'expanded'      => false,
                'multiple'      => true,
                'placeholder' => 'app.ui_element.field.select_option',
            ])

            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
