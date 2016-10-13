<?php
namespace AcreditacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AcreditacionBundle\Entity\Departamento;
use AcreditacionBundle\Entity\Municipio;
use AcreditacionBundle\Entity\CentroEducativo;
use AcreditacionBundle\Entity\CuotaAnualPorGradoEscolarPorCentroEducativo;
use AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo;
use AcreditacionBundle\Entity\Formulario;
use AcreditacionBundle\Entity\FormularioPorCentroEducativo;
use AcreditacionBundle\Entity\SeccionPorFormularioPorCentroEducativo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AcreditacionBundle\Form\CentroEducativoType;
use AcreditacionBundle\Form\CuotaAnualPorGradoEscolarPorCentroEducativoType;



class ReportesController extends Controller{


    public function cuantitativo_cualitativoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_cedu = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        return $this->render('reportes/reporte.CuantitativoCualidativo.html.twig',array(
            'lista_cedu'=>$lista_cedu
            ));
    }
    
    public function estadoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        $lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        return $this->render('reportes/reporte.Estado.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            'lista_departamento'    =>$lista_departamento,
            'lista_municipio'       =>$lista_municipio
        ));
    }
    
    public function zonaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        $lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        return $this->render('reportes/reporte.Zona.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            'lista_departamento'    =>$lista_departamento,
            'lista_municipio'       =>$lista_municipio
        ));
    }
    
    public function rango_fechaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        $lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        return $this->render('reportes/reporte.RangoFecha.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            'lista_departamento'    =>$lista_departamento,
            'lista_municipio'       =>$lista_municipio
        ));
    }


}
