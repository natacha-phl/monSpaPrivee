<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Department;
use App\Entity\Spa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SpaForPartnersType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du SPA',
//                'constraints' => new NotBlank(['message' => 'Veuillez entrer le nom de votre SPA'])
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du SPA'
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numéro de Siret'
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone du SPA'
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'label' => 'Département du spa',
                'placeholder' => 'Séléctionnez votre département',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville du spa',
                'placeholder' => 'Séléctionnez votre ville',
            ])
            ->add('zipCode', TextType::class, ['label' => 'Code postal du Spa'])
            ->add('street', TextType::class, ['label' => 'Rue du Spa'])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter']);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spa::class,
        ]);
    }

}



