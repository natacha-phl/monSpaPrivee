<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    // ....

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder


        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer la demande',
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'room'=> null,
            'user'=> null,
        ]);
    }
}
