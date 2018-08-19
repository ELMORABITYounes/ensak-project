<?php

namespace AppBundle\Form;

use AppBundle\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditDepartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id",HiddenType::class)
            ->add('name',TextType::class,array('label' => 'Nom du département'))
            ->add('description',TextareaType::class,array('label' => 'Déscription du département'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}
