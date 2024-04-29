<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom',
                'constraints' => [new NotBlank(), new Regex([
                    'pattern' => '/^[a-zA-ZÀ-ÿ\s\'-]{2,}$/',
                    'message' => 'Le prénom ne peut contenir des caractères spéciaux et doit conenir au moins 2 caractères'
                ])
                ]])
            ->add('lastName', TextType::class, ['label' => 'Nom',
                'constraints' => [new NotBlank(), new Regex([
                    'pattern' => '/^[a-zA-ZÀ-ÿ\s\'-]+$/',
                    'message' => 'Le nom ne peut contenir des caractères spéciaux et doit contenir au moins 2 caractères'
                ])
                ]])
            ->add('email', EmailType::class, ['label' => ' Adresse e-mail'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe',
                'constraints' => [new NotBlank(), new Regex([
                    'pattern' => '/^(?=.*\d)(?=.*[A-Z])(?=.*[!@#$%^&*()-+=])[A-Za-z\d!@#$%^&*()-+=]{12,}$/',
                    'message' => 'Le mot de passe doit contenir au moins un chiffre, une majuscule, un caractère spécial et avoir une longueur minimale de 12 caractères.'
                ])]])
            ->add('submit', SubmitType::class, ['label' => 'Créer un compte']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
