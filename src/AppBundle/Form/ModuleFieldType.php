<?php

namespace AppBundle\Form;

use AppBundle\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('modules', EntityType::class, array(
                'class' => 'AppBundle\Entity\Module',
                'choice_label' => 'libelle',
                'label' => 'Modules Enseignés',
                'placeholder'=>"--selectionez les modules à enseigner--",
                'group_by' => function (Module $module) {
                    return $module->getDepartement()->getName();
                },
                "attr"=>array("id"=>"modulesSelect","class"=>"multipleSelect"),
                'multiple' => true,
                //you need add this to the form in order to read the data
                'data'=>$options['data'],
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_module_field_type';
    }
}
