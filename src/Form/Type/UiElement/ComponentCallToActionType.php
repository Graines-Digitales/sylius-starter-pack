<?php

declare(strict_types=1);

namespace App\Form\Type\UiElement;

use App\Entity\IconMediaObject;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\IconMediaObjectTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MonsieurBiz\SyliusRichEditorPlugin\Form\Type\WysiwygType;


class ComponentCallToActionType extends AbstractType
{
    private $slugger;

    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_slug', TextType::class, [
                'disabled' => true,
                'data' => (isset($options['data']['slug']))? $options['data']['slug']: '',
                'label' => 'app.ui_element.field.slug',
                'mapped' => false,
                'help' => 'This field will be automatically edited',
                'required' => false,
            ])
            ->add('slug', HiddenType::class, [
                'disabled' => false,
            ])
            ->add('designation', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.designation',
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['groups' => ['component_contact_form_validation']])
                ],
                'label' => 'app.ui_element.field.title',
            ])
            ->add('subtitle', TextType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.subtitle',
            ])
            ->add('content', WysiwygType::class, [
                'required' => false,
                'label' => 'app.ui_element.field.content',
            ])
            ->add('icon', EntityType::class, [
                'required' => false,
                'class' => IconMediaObject::class,
                'placeholder' => 'app.ui_element.field.select_icon',
            ])
        ;

        $builder
            ->get('icon')
            ->addModelTransformer(new IconMediaObjectTransformer($this->manager))
        ;
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            $form = $event->getForm();
            if(empty($data['slug'])) {
                $string = $form->getConfig()->getName() . ' ' . $data['title'];
                $data['slug'] = $this->slugger->slug($string)->lower()->toString();
            }
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['component_contact_form_validation'],
        ]);
    }
}
