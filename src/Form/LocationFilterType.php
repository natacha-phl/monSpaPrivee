<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Department;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationFilterType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options)

    {


        $builder
            /*            ->add('region', EntityType::class, [
                            'choice_label' => 'name',
                            'class' => Region::class,
                            'required' => false,
                            'placeholder' => 'Séléctionnez une region',
                        ])*/
            ->add('department', ChoiceType::class, [
                'choices' => $options['departments'],
                'required' => false,
                'placeholder' => 'Séléctionnez un département',
            ])
            ->add('city', ChoiceType::class, [
                'choices' => $options['cities'],
                'required' => false,
                'placeholder' => 'Séléctionnez une ville',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Spa::class,
//            'method' => 'GET',
            'csrf_protection' => false, //on désactive car on dans un formulaire de recherche pas de probleme de s"cu
            'attr' => ['id' => 'location-filter'],
            'departments' => [],
            'cities' => []
        ]);


    }
}
