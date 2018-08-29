<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecteurActiviteFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('secteurActivite', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\SecteurActivite',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => "Secteur d'activités",
                'placeholder'=>"Choisissez un secteur d'activités",
                "attr"=>array("class"=>"multipleSelect"),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_secteur_activite_field_type';
    }
}
