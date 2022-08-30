<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Form\Type\PropertyValueType;
use App\WebContent\Component;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\Type\PropertyValueWebPageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ComponentLinkType extends AbstractType
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
        $code = 'component_link';
        // $templates = $this->componentService->getTemplates($code); // Depends on the code specified on the component creation
        // $styles = $this->componentService->getStyles($code);
        // $icons = $this->componentService->getIcons($code);
        // $configurationProject = $this->container->getParameter('configuration_project');

        $builder
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('label', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.label',
            ])
            ->add('web_page_link', PropertyValueWebPageType::class, [
                'by_reference' => false,
                'label' => 'app.ui_element.field.link_to_webpage',
                'block_name' => 'entry',
                'required' => false,
            ])
            // ->add('article_link', PropertyValueArticleType::class, [
            //     'by_reference' => false,
            //     'label' => 'app.ui_element.field.link_to_article',
            //     'block_name' => 'entry',
            //     'required' => false,
            //     // 'label' => 'app.ui_element.field.link',
            //     // 'constraints' => [
            //     //     new Assert\Url([]),
            //     // ],
            // ])
            ->add('external_link', PropertyValueType::class, [
                'by_reference' => false,
                'label' => 'app.ui_element.field.link_to_external_link',
                'block_name' => 'entry',
                'required' => false,
                // 'label' => 'app.ui_element.field.link',
                // 'constraints' => [
                //     new Assert\Url([]),
                // ],
            ])
            // ->add('template', ChoiceType::class, [
            //     'choices' => $templates,
            //     'required' => true,
            // ])
            // ->add('style', ChoiceType::class, [
            //     'choices' => $styles,
            //     'required' => true,
            // ])
        ;

    }
}
