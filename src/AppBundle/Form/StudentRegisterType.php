<?php

namespace AppBundle\Form;

use AppBundle\Entity\Niveau;
use AppBundle\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,array('label' => 'Prénom','attr' => array(
                'placeholder' => "Entrez votre nom"
            )
            ))
            ->add('secondName',TextType::class,array('label' => 'Nom','attr' => array(
                'placeholder' => "Entrez votre Prénom"
            )))
            ->add('niveau', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Niveau',

                // uses the User.username property as the visible option string
                'choice_label' => 'libelle',
                'label' => 'NIVEAU D\'ÉTUDES',
                'placeholder'=>"Choisir un niveau",
                'group_by' => function (Niveau $niveau) {
                    return $niveau->getFiliere();
                }
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add("cne",NumberType::class,array('label' => 'CNE','attr' => array(
                'placeholder' => "1465566555"
            )))
            ->add('email', EmailType::class,array('label' => 'Addresse émail','attr' => array(
                'placeholder' => "exemple@domaine.com"
            )))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password','translation_domain' => 'validators','attr' => array(
                    'placeholder' => "Mot de passe"
                )),
                'second_options' => array('label' => 'Repeat Password','translation_domain' => 'validators','attr' => array(
                    'placeholder' => "Confirmez le mot de passe"
                )),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Student::class,
        ));
    }

}
