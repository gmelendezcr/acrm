<?php
namespace AcreditacionBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuotaAnualPorGradoEscolarPorCentroEducativoType extends AbstractType{
    public function __construct($em){
        
        $nivelxgrado = $em->getRepository('AcreditacionBundle:NivelEducativo')->findAll();
        $ng=array();
        $ng['X']=array();
        foreach($nivelxgrado as $nivel){
//echo "N: " . $nivel->getNbrNivelEducativo();
            $ng['X'][$nivel->getNbrNivelEducativo()]=array();
                foreach($nivel->getGradosEscolares() as $grado){
//echo "G: " . $grado->getNbrGradoEscolar();
                $ng['X'][$nivel->getNbrNivelEducativo()][$grado->getNbrGradoEscolar()]=$grado;
                    
                }
        }
//var_dump($ng);
        $this->comboNivel=array(
/*
            'choices' => array(
                'X' => array(
                    'Y' => $em->getRepository('AcreditacionBundle:GradoEscolar')->find(1),
                    'Z' => $em->getRepository('AcreditacionBundle:GradoEscolar')->find(2),
                ),
            ),
*/
            'choices' => $ng,
            'choices_as_values' => true,
            'label' => 'Grado'
        );
        
        
        
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     
    public function buildForm(FormBuilderInterface $builder, array $options){
       
        $builder
        ->add('idGradoEscolarPorCentroEducativo',null,$this->comboNivel)
        ->add('anno','text',array('label' => 'Año'));
    }
  
    public function getName() { return 'CuotaAnualPorGradoEscolarPorCentroEducativoType'; }
}
