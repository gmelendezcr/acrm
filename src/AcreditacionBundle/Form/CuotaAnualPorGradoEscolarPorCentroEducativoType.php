<?php
namespace AcreditacionBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
        
    ///////////////////////////////////////////////////////
    
    //
    /*$res_nivel=$em->getRepository('AcreditacionBundle:NivelEducativo')->findAll();
    $nxg=array();
    foreach($res_nivel as $item){
        $nxg[$item->getNbrNivelEducativo()]=array();
        foreach($item->getGradosEscolares() as $grado){
            $nxg[$grado->getNbrGradoEscolar()]=array();
            $nxg[$item->getNbrNivelEducativo()][$grado->getNbrGradoEscolar()]=$grado;
        }
    }       
    $this->comboNG=array(
        'choices' => $nxg,
        'choices_as_values' => true,
        'label' => 'Grados'
    );*/
    
    $res_nivel=$em->getRepository('AcreditacionBundle:NivelEducativo')->findAll();
    $nxg=array();
    foreach($res_nivel as $item){
        $nxg[$item->getNbrNivelEducativo()]=array();
        foreach($item->getGradosEscolares() as $grado){
            $nxg[$grado->getNbrGradoEscolar()]=array();
            $nxg[$item->getNbrNivelEducativo()][$grado->getNbrGradoEscolar()]=$grado;
        }
    }       
    $this->comboNG=array(
        'choices' => $nxg,
        'choices_as_values' => true,
        'label' => 'Grados'
    );
        
        
    
 
        
        
        ///////////////////////////////////////////////
    //var_dump( $this->comboNG);
    
        /*->add('idGradoEscolarPorCentroEducativo', 'choice', 
            array(
                'label' => 'Estado',
                'choices' => array(
                    'Activo' => 'S',
                    'Inactivo' => 'N'
                ),
                'data' =>false,
                'choices_as_values' => true,
            )
        )*/
       $this->em = $em; 
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     
    public function buildForm(FormBuilderInterface $builder, array $options){
        //$dato = $builder->getData()->getIdGradoEscolarPorCentroEducativo();
        $builder
        ->add('matricula','text',array('label' => 'Matrícula'))
        ->add('monto','text',array('label' => 'Cuota'))
        ->add('anno','text',array('label' => 'Año'))
        ->add('idGradoEscolarPorCentroEducativo','choice', $this->comboNG);
        //->add('idGradoEscolarPorCentroEducativo','choice',$this->comboNG );
    }
    public function getName() { return 'CuotaAnualPorGradoEscolarPorCentroEducativoType'; }
}
