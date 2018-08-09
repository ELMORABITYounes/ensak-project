<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('_username',EmailType::class,['translation_domain' => 'validators'])
            ->add('_password', PasswordType::class,['translation_domain' => 'validators'])
            ->add("_remember_me",CheckboxType::class,['required' =>false,'translation_domain' => 'validators']);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'test',
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
