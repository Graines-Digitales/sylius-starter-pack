<?php

namespace App\Form\Type;

use App\Entity\WebPage;
use App\Entity\PropertyValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\WebPageTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertyValueWebPageType extends AbstractType
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('webPage', EntityType::class, [
                'class' => WebPage::class,
                'label' => ' ',
                'placeholder' => 'app.ui_element.field.select_web_page',
            ])
            // ->add('name', TextType::class, [
            //     'required' => true,
            //     'label' => 'app.ui_element.field.define_web_page_label',
            // ])
        ;

        $builder
            ->get('webPage')
            ->addModelTransformer(new WebPageTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => PropertyValue::class,
        ]);
    }
}
