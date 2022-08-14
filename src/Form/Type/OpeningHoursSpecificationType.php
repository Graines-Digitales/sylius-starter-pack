<?php

namespace App\Form\Type;

use App\Entity\OpeningHoursSpecification;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpeningHoursSpecificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('closes')
            // ->add('dayOfWeek')
            // ->add('opens')
            // ->add('validFrom')
            // ->add('validTrough')
            ->add('name')
            ->add('description', CKEditorType::class)
            // ->add('url')
            // ->add('mainEntityOfPage')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('localBusiness')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpeningHoursSpecification::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_opening_hours_specification';
    }
}
