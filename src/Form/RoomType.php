<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\Room;
use App\Entity\Spa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $spas = null;

        $builder
            ->add('spa', EntityType::class, [
                'class' =>Spa::class,
                'choice_label' => 'name',
                'choices' => $options['spas'],
                'multiple' => false,
                'expanded' => false

            ])
            ->add('name')
            ->add('description')
            ->add('capacity')
            ->add('priceHour')
            ->add('image')
            ->add('equipment', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Ajouter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
            'spas' => null
        ]);

    }
}
