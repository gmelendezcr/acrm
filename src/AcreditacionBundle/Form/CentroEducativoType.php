<?php
namespace AcreditacionBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentroEducativoType extends AbstractType{
    public function __construct($em){
        //Gerardo
        $resZona=$em->getRepository('AcreditacionBundle:ZonaGeografica')->findAll();
        $muni=array();
        foreach($resZona as $zona){
            $muni[$zona->getNbrZonaGeografica()]=array();
            foreach($zona->getDepartamentos() as $departamento){
                $muni[$zona->getNbrZonaGeografica()]['-' . $departamento->getNbrDepartamento()]=array();
                foreach($departamento->getMunicipios() as $municipio){
                    $muni[$zona->getNbrZonaGeografica()]['-' . $departamento->getNbrDepartamento()][$municipio->getNbrMunicipio()]=$municipio;
                }
            }
        }
        $this->comboMun=array(
            'choices' => $muni,
            'choices_as_values' => true,
            'label' => 'Municipio'
        );
        //Fin Gerardo
        
        $resJornada=$em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->findAll();
        //$jda=array();
        foreach($resJornada as $jornada){
             //$jda[$jornada->getNbrJornadaCentroEducativo()]=array();
             $jda[$jornada->getNbrJornadaCentroEducativo()]=$jornada;
         }
         
         $this->comboJda=array(
            'choices' => $jda,
            'choices_as_values' => true,
            'label' => 'Jornadas'
        );
        
        $resTamanno=$em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        //$jda=array();
        foreach($resTamanno as $tamanno){
             //$jda[$jornada->getNbrJornadaCentroEducativo()]=array();
             $tnno[$tamanno->getNbrTamannoCentroEducativo()]=$tamanno;
         }
         
         $this->comboTnno=array(
            'choices' => $tnno,
            'choices_as_values' => true,
            'label' => 'Tamaño'
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     
    public function buildForm(FormBuilderInterface $builder, array $options){
        /*Probando code*/
         
        /*Fin*/
        
        $estado = $builder->getData()->getActivo();
        $builder
        ->add('codCentroEducativo','text',array('label' => 'Código'))
        ->add('nbrCentroEducativo', 'text',array('label' => 'Nombre'))
        ->add('direccionCentroEducativo', 'text',array('label' => 'Dirección'))
        ->add('totalAlumnos', 'text',array('label' => 'Total de alumnos'))
        ->add('idMunicipio',null,$this->comboMun)
        ->add('idJornadaCentroEducativo',null,$this->comboJda)
        ->add('idTamannoCentroEducativo',null,$this->comboTnno)
        ->add('activo', 'choice', 
            array(
                'label' => 'Estado',
                'choices' => array(
                    'Activo' => 'S',
                    'Inactivo' => 'N'
                ),
                'data' =>$estado,
                'choices_as_values' => true,
            )
        )
          ->add('Guardar', 'submit');
    }
  
    public function getName() { return 'CentroEducativoType'; }
}