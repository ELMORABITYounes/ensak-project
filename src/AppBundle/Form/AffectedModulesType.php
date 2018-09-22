<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectedModulesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('modules', EntityType::class, array(
                'class' => 'AppBundle\Entity\Module',
                'choice_label' => 'libelle',
                'label' => 'Modules enseigné par le professeur',
                'placeholder'=>"--Prière de séléctionner les modules à affécter--",
                'multiple' => true          ,
                'choices'=>$options["modules"],
                "attr"=>array("class"=>"multipleSelect"),
                "required"=>false,
                "data"=>$options["data"],
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'modules' => null
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_affected_modules_type';
    }
}
