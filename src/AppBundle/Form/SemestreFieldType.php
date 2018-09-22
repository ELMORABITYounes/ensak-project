<?php

namespace AppBundle\Form;

use AppBundle\Entity\Semestre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemestreFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('semestre', EntityType::class, array(
                'class' => 'AppBundle\Entity\Semestre',
                'choice_label' => 'libelle',
                'label' => 'Semestre d\'enseignement',
                'placeholder'=>"--selectionez un semestre--",
                'group_by' => function (Semestre $semestre) {
                    return $semestre->getNiveau()->getFiliere()->getName();
                }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_semestre_field_type';
    }
}
