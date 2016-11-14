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
        $ng['X'][$nivel->getNbrNivelEducativo()]=array();
            foreach($nivel->getGradosEscolares() as $grado){
                $ng['X'][$nivel->getNbrNivelEducativo()][$grado->getNbrGradoEscolar()]=$grado;
            }
        }
    $this->comboNivel=array(
    'choices' => $ng,
        'choices_as_values' => true,
        'label' => 'Grado'
    );
        
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
    
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('matricula','text',array('label' => 'Matrícula'))
        ->add('monto','text',array('label' => 'Colegiatura'))
        ->add('anno','text',array('label' => 'Año'))
        ->add('cantidadCuotas','text',array('label' => 'Número de colegiaturas'));
    }
    public function getName() { return 'CuotaAnualPorGradoEscolarPorCentroEducativoType'; }
}
