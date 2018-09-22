<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditTeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('firstName',TextType::class,array('label' => 'Prénom'))
            ->add('secondName',TextType::class,array('label' => 'Nom'))
            ->add('departement', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Departement',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'département',
                "placeholder"=>"--prière de choisir un département--"
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add("tel",TextType::class,array('label' => 'Numero de télephone(Optionnel)',"required"=>false))
            ->add("somme",NumberType::class,array('label' => 'Numero de somme'))
            ->add('email', EmailType::class,array('label' => 'Addresse émail'))
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'label' => "Image de profile(Optionnel)",
                'download_uri' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
