<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\SearchAction;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DataTransformer\TagsTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use App\Configuration\Project;

class SearchActionType extends AbstractResourceType
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
        /** transférer tous les getParameter dans le service dédié */
        $configurationProject = $this->container->getParameter('configuration_project');
        $builder
            // ->add('name', TextType::class, [
            //     'label' => 'app.ui_element.field.search_name'
            // ])
            ->add('mainEntityOfPage', ChoiceType::class, [
                'choices' => $this->configurationService->getMainEntityOfPageForChoiceType(),
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('tags', EntityType::class, [
                'class'         => Category::class,
                'expanded'      => true,
                'multiple'      => true,
                // 'by_reference' => false,
                'placeholder' => 'app.ui_element.field.select_option',
            ])
            ->add('orderByDate', ChoiceType::class, [
                'choices' => $configurationProject['search_action']['order'],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('limitResult', IntegerType::class)
        ;
            
        $builder
            ->get('tags')
            ->addModelTransformer(new TagsTransformer($this->manager));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            // Change image field constraints depending on submitted value
            
            // $options = $event->getForm()->get('image')->getConfig()->getOptions();
            // $options['constraints'] = RichEditorConstraints::getImageConstraints($event->getData(), 'image', false);
            // $event->getForm()->add('image', FileType::class, $options);
            // $event->getForm()->get('query')->setData(['order' => 'asc']);
            // dump($event->getForm()->get('query')->getData());die;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_search_action';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => SearchAction::class,
        ]);
    }
}
