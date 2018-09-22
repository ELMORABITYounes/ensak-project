<?php

namespace AppBundle\Form;


use AppBundle\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherRegisterType extends AbstractType
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
            ->add('departement', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Departement',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'département',
                'placeholder' => '--choisissez votre département--'
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add("somme",NumberType::class,array('label' => 'Numero de somme','attr' => array(
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
            'data_class' => Teacher::class,
        ));
    }
}
