<?php

namespace App\Form\Type;

use App\Entity\Article;
use App\Entity\PropertyValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\ArticleTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertyValueArticleType extends AbstractType
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'label' => ' ',
                'placeholder' => 'app.ui_element.field.select_article',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'app.ui_element.field.define_article_label',
            ])
        ;

        $builder
            ->get('article')
            ->addModelTransformer(new ArticleTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => PropertyValue::class,
        ]);
    }
}
