<?php

namespace AppBundle\Form;

use AppBundle\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NiveauFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('niveau', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Niveau',

                // uses the User.username property as the visible option string
                'choice_label' => 'libelle',
                'label' => "Lister par niveau d'études:",
                'placeholder'=>"Choisissez un niveau",
                'group_by' => function (Niveau $niveau) {
                    return $niveau->getFiliere();
                },
                "attr"=>array("id"=>"niveau")
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_niveau_type';
    }
}
