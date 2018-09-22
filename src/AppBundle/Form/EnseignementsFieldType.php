<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignementsFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('modules', EntityType::class, array(
        'class' => 'AppBundle\Entity\Module',
        'choice_label' => 'libelle',
        'label' => 'Modules à affecter',
        'placeholder'=>"--selectionez les modules à affecter--",
        'multiple' => true          ,
            'choices'=>$options["modules"],
            "attr"=>array("class"=>"multipleSelect"),

        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'modules' => null
        ));
    }

}
