<?php

namespace App\Form\Type;

use App\Entity\HotelRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('maximumOccupants')
            ->add('labelBgTransparent')
            ->add('numberOfRooms')
            ->add('minimumOccupants')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category')
            ->add('amenityFeatures')
            ->add('events')
            ->add('offers')
            ->add('primaryImage')
            ->add('secondaryImage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HotelRoom::class,
            'validation_groups' => ['hotel_room_validation'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_hotel_room';
    }
}
