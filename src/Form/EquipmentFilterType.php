<?php

namespace App\Form;

use App\Data\EquipmentFilter;
use App\Entity\Equipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipments', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Equipment::class,
                'expanded' => true, //pour le checkbox
                'multiple' => true, // pour le checkbox
            ]);

    }
 public function configureOptions(OptionsResolver $resolver)
 {
     $resolver->setDefaults([
         'data_class'=>EquipmentFilter::class,
         'method'=>'GET',
         'csrf_protection' => false, //on désactive car on dans un formulaire de recherche pas de probleme de s"cu
         'attr'=>['id'=>'search']
     ]);



 }
}