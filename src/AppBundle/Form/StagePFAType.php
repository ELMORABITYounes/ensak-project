<?php

namespace AppBundle\Form;

use AppBundle\Entity\StagePFA;
use AppBundle\Entity\EncadrantExterne;
use AppBundle\Entity\Societe;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StagePFAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student', EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Student',
                'choice_label' =>function (Student $student) {
                    return $student->getFirstName().", ".$student->getSecondName();
                },
                'group_by' => function (Student $student) {
                    $niveau=$student->getNiveau();
                    return $niveau->getLibelle()." ".$niveau->getFiliere()->getName();
                },
                'label' => 'Etudiant',
                "attr"=>array("class"=>"multipleSelect"),
                "placeholder"=>"--Prière de choisir un étudiant--"
            ))
            ->add('professeurEncadrant', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => 'AppBundle\Entity\Teacher',
                    'choice_label' =>function (Teacher $teacher) {
                        return $teacher->getFirstName()." ".$teacher->getSecondName();
                    },
                    'group_by' => function (Teacher $teacher) {
                        return $teacher->getDepartement()->getName();
                    },
                    'label' => 'Professeur Encadrant',
                    "attr"=>array("class"=>"multipleSelect"),
                    "placeholder"=>"--Prière de choisir un professeur--"
                )
            )
            ->add('dateDebut', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('dateFin', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('technologies', TextType::class, array(
                "label"=>"technologies",
                'attr' => array(
                    'placeholder' => "technologie1,technologie2..."
                )))
            ->add('sujet', TextareaType::class, array(
                "label"=>"Sujet de Stage",
                'attr' => array(
                    'placeholder' => "Sujet de Stage"
                )))
            ->add("societe",EntityType::class, array(
                // looks for choices from this entity
                'class' => 'AppBundle\Entity\Societe',
                'choice_label' =>function (Societe $societe) {
                    return $societe->getName();
                },
                'group_by' => function (Societe $societe) {
                    return $societe->getVille();
                },
                'label' => 'Société',
                "placeholder"=>"--Prière de choisir une societe--",
                "attr"=>array("class"=>"multipleSelect")));

        $formModifier = function (FormInterface $form, Societe $societe = null) {
            $encadrants = null === $societe ? array() : $societe->getEncadrants();

            $form->add(
                'encadrantExtern', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => 'AppBundle\Entity\EncadrantExterne',
                    'choices'=>$encadrants,'choice_label' =>function (EncadrantExterne $encadrantExterne) {
                        return $encadrantExterne->getFirstName()." ".$encadrantExterne->getSecondName();
                    },
                    'label' => 'Encadrant/Superviseur',"attr"=>array("class"=>"multipleSelect")
                ,"placeholder"=>"--Prière de choisir un encadrant--"
                )
            );
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getSociete());
            }
        );

        $builder->get('societe')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $societe = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $societe);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StagePFA::class,
        ));
    }

}
