<?php

namespace AppBundle\Form;

use AppBundle\Entity\MembreJury;
use AppBundle\Entity\Soutenance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoutenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("dateSoutenance",DateTimeType::class)
            ->add('membres', CollectionType::class, array("label"=>"Membres de jury :",
                'entry_type' => MembreJuryType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Soutenance::class,
        ));
    }

}
