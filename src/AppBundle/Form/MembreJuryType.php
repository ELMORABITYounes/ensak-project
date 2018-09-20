<?php

namespace AppBundle\Form;

use AppBundle\Entity\MembreJury;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreJuryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("firstName",TextType::class,["label"=>false,'attr' => array(
            'placeholder' => "Prénom du membre")])
            ->add("secondName",TextType::class,["label"=>false,'attr' => array(
                'placeholder' => "Nom du membre")])
            ->add("email",EmailType::class,["label"=>false,'attr' => array(
                'placeholder' => "Email du membre")])
            ->add("type", ChoiceType::class, array(
                'choices' => MembreJury::getTypes(),"label"=>false,
                'placeholder' => "--prière de choisir un role--"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MembreJury::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_memnbre_jury_type';
    }
}
