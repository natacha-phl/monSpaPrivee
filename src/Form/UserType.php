<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class,['label'=>'Prénom'])
            ->add('lastName',TextType::class,['label'=>'Nom'])
            ->add('address', AddressType::class)
            ->add('email', EmailType::class,['label'=>' Adresse e-mail'])
            ->add('password',PasswordType::class,['label'=>'Password'])
            ->add('submit',SubmitType::class,['label'=>'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
