<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('name',TextType::class,array('label' => 'Nom de la société'))
            ->add("ville",TextType::class,array("label"=>"Ville"))
            ->add('address',TextType::class,array('label' => 'Addresse de la société'))
            ->add('secteursActivites', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\SecteurActivite',
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => "Secteurs d'activités",
                'placeholder'=>"--Prière de séléctionner les sécteurs d'activités--",
                "attr"=>array("class"=>"multipleSelect"),
                "multiple"=>true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_edit_societe_type';
    }
}
