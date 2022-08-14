<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\MediaObject;
use App\WebContent\Component;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Repository\MediaObjectRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\DataTransformer\MediaObjectTransformer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Constraints\RichEditorConstraints;

class ComponentHeroType extends AbstractType
{
    private $componentService;

    private $manager;
    
    private $container;

    public function __construct(
        Component $componentService,
        EntityManagerInterface $manager,
        ContainerInterface $container
    ){
        $this->componentService = $componentService;
        $this->manager = $manager;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $code = 'component_hero'; // Use the code defined on the component creation in admin pannel
        $templates = $this->componentService->getTemplates($code);
        $styles = $this->componentService->getStyles($code);
        $configurationProject = $this->container->getParameter('configuration_project');

        $builder
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.title',
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('primaryImage', EntityType::class, [
                'class' => MediaObject::class,
                'placeholder' => 'app.ui_element.field.select_primary_image',
                'query_builder' => function(MediaObjectRepository $repo) use ($configurationProject){
                    return $repo->createQueryBuilderByEncodingImage($configurationProject);
                }
            ])
            ->add('label', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.label',
            ])
            ->add('link', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.link',
                'constraints' => [
                    new Assert\Url([]),
                ],
            ])
            ->add('template', ChoiceType::class, [
                'choices' => $templates,
                'required' => true,
            ])
            ->add('style', ChoiceType::class, [
                'choices' => $styles,
                'required' => true,
            ])
        ;
        
        $builder
        ->get('primaryImage')
        ->addModelTransformer(new MediaObjectTransformer($this->manager));

    }
}
