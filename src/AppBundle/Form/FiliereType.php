<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiliereType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class,array('label' => 'Nom du Filiere'))
        ->add('premiere',TextType::class,array('label' => 'Nom du premiere année'))
        ->add('deuxieme',TextType::class,array('label' => 'Nom du deuxième année'))
        ->add('troisieme',TextType::class,array('label' => 'Nom du troisième année'))
            ->add('nbrSemestres', ChoiceType::class, array(
                'choices'  => array(
                    '5 semestres' => 5,
                    '6 semestres' => 6,
                )
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_filiere';
    }


}
