<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Spa;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom du SPA'])
            ->add('description', TextType::class,[
                'label'=>'Description du SPA '])
            ->add('siret', TextType::class,[
                'label'=>'Numéro de Siret'])
            ->add('phoneNumber', TextType::class,[
                'label'=>'Numéro de téléphone du SPA'])
            ->add('image', TextType::class,[
                'label'=>'Image du SPA'])
            ->add('user', UserType::class)
            //->add('address', AddressType::class)
            //->add('user', UserType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spa::class,
        ]);
    }

}



