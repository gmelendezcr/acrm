<?php
namespace AcreditacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AcreditacionBundle\Entity\Departamento;
use AcreditacionBundle\Entity\Municipio;
use AcreditacionBundle\Entity\CentroEducativo;
use AcreditacionBundle\Entity\Formulario;
use AcreditacionBundle\Entity\FormularioPorCentroEducativo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CentroEducativoController extends Controller{
    public function listaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        //var_dump($lista);
        return $this->render('centro-educativo/lista.index.html.twig',array(
            'lista'=>$lista    
        ));
    }
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $departamentos = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $municipios = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        $jornadas = $em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->findAll();
        $tamannos = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        return $this->render('centro-educativo/addcedu.index.html.twig', array(
            'departamentos' => $departamentos,
            'municipios'=> $municipios,
            'jornadas'=> $jornadas,
            'tamannos'=> $tamannos
        ));
    }
    
    public function addguardarAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $codigo=$request->get('codigo');
        $nombre=$request->get('nombre');
        $direccion=$request->get('direccion');
        $total_alumnos=$request->get('total_alumnos');
        $municipios=$request->get('municipios');
        $municipio = $em->getRepository('AcreditacionBundle:Municipio')->find($municipios);
        //$jornadas=$request->get('jornadas');
        //$tamanno=$request->get('tamanno');
        $ce=new CentroEducativo();
        $ce->setCodCentroEducativo($codigo);
        $ce->setNbrCentroEducativo($nombre);
        $ce->setDireccionCentroEducativo($direccion);
        $ce->setTotalAlumnos($total_alumnos);
        $ce->setIdMunicipio($municipio);
        //$ce->setIdJornadaCentroEducativo($jornadas);
        //$ce->setIdTamannoCentroEducativo($tamanno);
        $ce->setActivo('S');
        $em=$this->getDoctrine()->getManager();
        $em->persist($ce);
        $em->flush();
        $lista = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        return $this->render('centro-educativo/lista.index.html.twig',array(
        'lista'=>$lista    
        ));
    }
    
    public function borrarAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $check_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        $em->remove($check_ced);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_lista');
    }
    
    public function editarAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $check_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        return $this->render('centro-educativo/lista.index.html.twig',array(
        'centro_educativo'=>$check_ced       
        ));
    }
    
    public function form_dig_corrAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        $lista_form = $em->getRepository('AcreditacionBundle:Formulario')->findAll();
        //$lista_form_estado = $em->getRepository('AcreditacionBundle:EstadoFormulario')->findAll();
        return $this->render('centro-educativo/form_dig_corr.index.html.twig',array(
        'lista_ced'=>$lista_ced,
        'lista_form'=>$lista_form,
        //'lista_form_estado'=>$lista_form_estado
        ));
    }
    public function form_lista_revisarAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        //var_dump($lista);
        return $this->render('centro-educativo/form_lista_revisar.index.html.twig',array(
            'lista'=>$lista    
        ));
    }
    public function form_lista_evaluarAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        //var_dump($lista);
        return $this->render('centro-educativo/form_lista_evaluar.index.html.twig',array(
            'lista'=>$lista    
        ));
    }

    public function digitarCorregirCargarAction(Request $request){
        $idCentroEducativo=$request->get('centrosEducativos');
        $idFormulario=$request->get('formularios');
        $lugarAplicacion=$request->get('lugarAplicacion');
        $fechaAplicacion=$request->get('fechaAplicacion');

        $em = $this->getDoctrine()->getEntityManager();
        $formularioPorCentroEducativo=new FormularioPorCentroEducativo();
        $formularioPorCentroEducativo->setIdCentroEducativo($em->getRepository('AcreditacionBundle:CentroEducativo')->find($idCentroEducativo));
        $formularioPorCentroEducativo->setIdFormulario($em->getRepository('AcreditacionBundle:Formulario')->find($idFormulario));
        $formularioPorCentroEducativo->setLugarAplicacion($lugarAplicacion);
        $formularioPorCentroEducativo->setFechaAplicacion(new \DateTime($fechaAplicacion));
        $formularioPorCentroEducativo->setIdUsuarioDigita($em->getRepository('AcreditacionBundle:Usuario')->find($this->getUser()->getId()));
        $em->persist($formularioPorCentroEducativo);
        $em->flush();

        $session = new Session();
        $session->set('idFormularioPorCentroEducativo', $formularioPorCentroEducativo->getIdFormularioPorCentroEducativo());

        return $this->redirectToRoute('seccion_index');
    }
}