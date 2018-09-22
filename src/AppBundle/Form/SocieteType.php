<?php

namespace AppBundle\Form;

use AppBundle\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,array('label' => 'Nom de la société'))
            ->add("ville",TextType::class,array("label"=>"Ville"))
            ->add('address',TextType::class,array('label' => 'Addresse de la société'))
            ->add('secteursActivites', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\SecteurActivite',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => "Secteurs d'activités",
                'placeholder'=>"--Selectionnez les secteur d'activités--",
                "attr"=>array("class"=>"multipleSelect"),
                "multiple"=>true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Societe::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_societe_type';
    }
}
