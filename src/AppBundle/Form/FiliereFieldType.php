<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiliereFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filiere', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Filiere',
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'Selectionner un Filiere pour le Consulter:',
                'placeholder'=>"--Choisissez un Filière--"
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_filiere_field_type';
    }
}
