<?php

namespace AppBundle\Form;

use AppBundle\Entity\EncadrantExterne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncadrantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Societe',
                'choice_label' => 'name',
                'label' => "Nom de Société",
                'placeholder'=>"Choisissez une société",
                "data"=>$options["societe"]
            ))
            ->add('firstName',TextType::class,array('label' => 'Prénom'))
            ->add('secondName',TextType::class,array('label' => 'Nom'))
            ->add("tel",TextType::class,array('label' => 'Numero de télephone',"required"=>false))
            ->add('email', EmailType::class,array('label' => 'Addresse émail'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EncadrantExterne::class,
            'societe'=>null
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_encadrant_type';
    }
}
