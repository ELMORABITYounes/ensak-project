<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartementFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departemrnt', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Departement',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'Département',
                'placeholder'=>"Choisir un département"
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_departement_type';
    }
}
