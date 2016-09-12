<?php

namespace AcreditacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null,array(
                'label' =>'Usuario',
            ))
            ->add('nombres')
            ->add('apellidos')
            ->add('email',null,array(
                'label' =>'Correo electrónico',
            ))
            ->add('password','password')
            ->add('passwordConfirmation', 'password', array(
                'label' =>'Confirmación',
                'mapped' => false
            ))
            ->add('enabled',null,array(
                'label' =>'Activo',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AcreditacionBundle\Entity\Usuario'
        ));
    }
}
