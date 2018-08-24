<?php

namespace AppBundle\Form;

use AppBundle\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departement', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Departement',

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'label' => 'département'

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add('libelle',TextType::class,array('label' => 'Nom de module'))
            ->add('nbrHCours',NumberType::class,array('label' => 'Nombre d\'heures de Cours'))
            ->add('nbrHTd',NumberType::class,array('label' => 'Nombre d\'heures de TD'))
            ->add('cahierFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'label' => "Cahier de charges",
                'download_uri' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Module::class,
        ));
    }

}
