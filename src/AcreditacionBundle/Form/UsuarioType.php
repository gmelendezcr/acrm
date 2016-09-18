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
            
            ->add('roles', null, array(
                    'type' => 'choice',
                    'label' => 'Roles',
                    'mapped' => true,
                    'options' => array(
                        'label' => false,
                        'choices' => array('ROLE_USER' => 'Digitador', 'ROLE_ADMIN' => 'Evaluador'),
                        'multiple' => false,
                        'data' => 1
                    ),
                    
                    
            ));
        
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
