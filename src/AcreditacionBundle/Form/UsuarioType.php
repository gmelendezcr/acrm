<?php

namespace AcreditacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     
  
    
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
 
        
/*
        $permisos = array(
        'ROLE_DIG'        => 'Digitador',
        
    );
    
     $permissions = array(
             'ROLE_USER'        => 'Digitador',
             'ROLE_CONSULT'     => 'Corrector',
             'ROLE_SUPER_ADMIN' => 'Calificador'
         );
*/

        
    /*->add('roles', null, array(
                    'type' => 'choice',
                    'label' => 'Roles',
                    'mapped' => true,
                    'options' => array(
                        'label' => false,
                        'choices' => $permissions,
                        'multiple' => false,
                        'data' => 1
                    ),
                    
                    
            ));*/
       
        
        $builder
            ->add('username',null,array(
                'label' =>'Usuario',
            ))
            ->add('nombres')
            ->add('apellidos')
            ->add('email',null,array(
                'label' =>'Correo electrónico',
            ))
            ->add('password',PasswordType::class, array('required' => true, 'label'=>'Clave','attr' => array('value'=>'abcd')))
            ->add('passwordConfirmation', PasswordType::class, array(
                'label' =>'Confirmación de clave',
                'mapped' => false,
                'required' => true,
                'attr' => array('value'=>'abcd'),
                
            ))
            ->add('enabled',null,array(
                'label' =>'Activo',
            ))
            ->add('roles', ChoiceType::class, array(
                'choices'  => array(
                    'Digitador' => 'ROLE_DIGITADOR',
                    'Revisor' => 'ROLE_REVISOR',
                    'Coordinador' => 'ROLE_COORDINADOR',
                    'Acreditador' => 'ROLE_ACREDITADOR',
                    'MINED' => 'ROLE_MINED',
                    'Administrador' => 'ROLE_SUPER_ADMIN',
                ),
                'choices_as_values' => true,
                'multiple' => true,
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
