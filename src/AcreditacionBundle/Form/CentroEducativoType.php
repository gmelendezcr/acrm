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
            'label' => 'Municipio',
            'empty_value' => false,
            
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
            'label' => 'Jornadas',
            'empty_value' => false,
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
            'label' => 'Tamaño',
            'empty_value' => false,
        );

        $zonaCentroEducativo=array();
        $resZonaCentroEducativo=$em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->findAll();
        foreach($resZonaCentroEducativo as $regZonaCentroEducativo){
            $zonaCentroEducativo[$regZonaCentroEducativo->getNbrZonaCentroEducativo()]=$regZonaCentroEducativo;
        }
        
        $this->comboZonaCentroEducativo=array(
            'choices' => $zonaCentroEducativo,
            'choices_as_values' => true,
            'label' => 'Zona',
        );

        $modalidadCentroEducativo=array();
        $resModalidadCentroEducativo=$em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->findAll();
        foreach($resModalidadCentroEducativo as $regModalidadCentroEducativo){
            $modalidadCentroEducativo[$regModalidadCentroEducativo->getNbrModalidadCentroEducativo()]=$regModalidadCentroEducativo;
        }
        $this->comboModalidadCentroEducativo=array(
            'choices' => $modalidadCentroEducativo,
            'choices_as_values' => true,
            'label' => 'Modalidad',
            'empty_value' => false,
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
        ->add('idZonaCentroEducativo',null,$this->comboZonaCentroEducativo)
        ->add('idModalidadCentroEducativo',null,$this->comboModalidadCentroEducativo)
        ->add('totalDocentesMasculinos', 'text',array('label' => 'Total de docentes masculinos'))
        ->add('totalDocentesFemeninos', 'text',array('label' => 'Total de docentes femeninos'))
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
        );
          
    }
  
    public function getName() { return 'CentroEducativoType'; }
}
