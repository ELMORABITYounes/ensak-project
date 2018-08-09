<?php

namespace AppBundle\Form;

use AppBundle\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,array('label' => 'Prénom'))
            ->add('secondName',TextType::class,array('label' => 'Nom'))
            ->add('departement',TextType::class,array('label' => 'département'))
            ->add("tel",TextType::class,array('label' => 'Numero de télephone',"required"=>false))
            ->add("somme",NumberType::class,array('label' => 'Numero de somme'))
            ->add('email', EmailType::class,array('label' => 'Addresse émail'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password','translation_domain' => 'validators'),
                'second_options' => array('label' => 'Repeat Password','translation_domain' => 'validators'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Teacher::class,
        ));
    }

}
