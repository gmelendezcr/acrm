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
    
    
    
    
    
    
    public function form_estado_actual_ceduAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_estado_acred = $em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findAll();
         return $this->render('reportes/formReporte.EstadoCEDU.html.twig',array(
            'lista_estado_acred'         =>$lista_estado_acred
        ));
        
    }
    
    
    
    
    
    
    
    
    
    
public function pdf_estado_actual_ceduAction(Request $request){
    $estado_acred=$request->get('estado_acred');
     $fecha=$request->get('fecha');
     $fechaRef=new \DateTime($fecha);
     
    $em = $this->getDoctrine()->getManager();
    $pdfObj=$this->get("white_october.tcpdf")->create();
    $pdfObj->setHeaderType('newLogoHeader');
    $pdfObj->setFooterType('simpleFooter');
    $pdfObj->startPageGroup();
    $pdfObj->AddPage();
    $pdfObj->MultiCell($pdfObj->getWorkAreaWidth(),$pdfObj->getLineHeight(),'Listado de centros educativos por estado actual',0,'C');
    $pdfObj->newLine();
    $pdfObj->SetFontSize(9);
    $msj="";
    if($estado_acred=="AC"){
        $msj="Lista de acreditados y acreditados con observaciones";
        //acreditados
        $lista_cedu=$em->createQueryBuilder()
        ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
        d.nbrDepartamento,
        m.nbrMunicipio,
        acred.fechaInicio,
        acred.fechaFin
        
        ')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        ->join('ce.acreditaciones','acred' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
            ->where('exists (
                select 1
                    from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                        where a.fechaInicio<=:fechaRef
                            and a.fechaFin>=:fechaRef
                            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a.idCentroEducativo=ce.idCentroEducativo
            )')
        ->setParameter('fechaRef',$fechaRef)
        ->getQuery()->getResult();
  
   }elseif($estado_acred=="VD"){
       $msj="Lista de vencidos";
        $lista_cedu=$em->createQueryBuilder()
        ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
        d.nbrDepartamento,
        m.nbrMunicipio,
        acred.fechaInicio,
        acred.fechaFin')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        ->join('ce.acreditaciones','acred' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
        ->where('exists (
            select 1
            from AcreditacionBundle:Acreditacion a1, AcreditacionBundle:EstadoAcreditacion e1
                where e1.codEstadoAcreditacion in (\'AC\',\'AO\')
                and a1.idEstadoAcreditacion=e1.idEstadoAcreditacion
                and a1.idCentroEducativo=ce.idCentroEducativo
        )')
        ->andWhere('not exists (
            select 1
            from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                where a.fechaInicio<=:fechaRef
                and a.fechaFin>=:fechaRef
                and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                and a.idCentroEducativo=ce.idCentroEducativo
        )')
        ->setParameter('fechaRef',$fechaRef)
        ->getQuery()->getResult();   
   }else{
        $msj="Lista por vencer";
       $lista_cedu=$em->createQueryBuilder()
        ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
        d.nbrDepartamento,
        m.nbrMunicipio,
        acred.fechaInicio,
        acred.fechaFin')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        ->join('ce.acreditaciones','acred' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
        ->where('exists (
            select 1
            from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
            where a.fechaInicio<=:fechaActual
            and a.fechaFin>=:fechaActual
            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
            and a.idCentroEducativo=ce.idCentroEducativo
        )')
        ->andWhere('not exists (
            select 1
            from AcreditacionBundle:Acreditacion a1, AcreditacionBundle:EstadoAcreditacion e1
            where a1.fechaInicio<=:fechaRef
            and a1.fechaFin>=:fechaRef
            and e1.codEstadoAcreditacion in (\'AC\',\'AO\')
            and a1.idEstadoAcreditacion=e1.idEstadoAcreditacion
            and a1.idCentroEducativo=ce.idCentroEducativo
        )')
        ->setParameter('fechaRef',$fechaRef)
        ->setParameter('fechaActual',new \DateTime()) //hoy
        ->getQuery()->getResult();
   }

    
    
    $html = '
        <style>
            table{
                color: #003300;
                font-family: helvetica;
                font-size: 8pt;
                background-color: #ccffcc;
            }
            table, td, th {
                border: 1px solid #CDCDCD;
                text-align: left;
            }
            td{
                padding:5px;
            }
            td.second {
                background-color: #ccffcc;
            }
        </style>
        
        <table border="0" cellpadding="4" cellpacing="4" style="">
            <tr>
                <th colspan="5" align="center">'.$msj.'</th>
            </tr>
            <tr bgcolor="#ccc" valign="middle">
                <th width="10%" valign="middle" rowspan="2"><br /><strong>Código</strong></th>
                <th width="30%" rowspan="2"><br /><strong>Nombre</strong></th>
                <th width="30%" rowspan="2"><br /><strong>Ubicación</strong></th>
                <th width="30%" colspan="2" align="center"><strong>Fecha</strong></th>
            </tr>
            <tr bgcolor="#ccc" valign="middle">
                <th width="15%" align="center"><strong>Inicial</strong></th>
                <th width="15%" align="center"><strong>Vencimiento</strong></th>
            </tr>
    ';
foreach ($lista_cedu as $cd) {
    $html .='
        <tr>
            <td>'.$cd["codCentroEducativo"].'</td>
            <td>'.$cd["nbrCentroEducativo"].'</td>
            <td>'.$cd["nbrDepartamento"].','.$cd["nbrMunicipio"].''.$cd["direccionCentroEducativo"].'</td>
            <td align="center">'.$cd["fechaInicio"]->format('d-m-Y').'</td>
            <td align="center">'.$cd["fechaFin"]->format('d-m-Y').'</td>
        </tr>';
}
    $html .='</table>';
    




/*
$fechaRef=new \DateTime('2022-10-14');

//acreditados
$acreditados=$em->createQueryBuilder()
    ->select('ce.codCentroEducativo, ce.nbrCentroEducativo')
    ->from('AcreditacionBundle:CentroEducativo', 'ce')
    ->where('exists (
        select 1
        from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
        where a.fechaInicio<=:fechaRef
        and a.fechaFin>=:fechaRef
        and e.codEstadoAcreditacion in (\'AC\',\'AO\')
        and a.idEstadoAcreditacion=e.idEstadoAcreditacion
        and a.idCentroEducativo=ce.idCentroEducativo
    )')
        ->setParameter('fechaRef',$fechaRef)
        ->getQuery()->getResult();

//vencidos
$vencidos=$em->createQueryBuilder()
    ->select('ce.codCentroEducativo, ce.nbrCentroEducativo')
    ->from('AcreditacionBundle:CentroEducativo', 'ce')
    ->where('exists (
        select 1
        from AcreditacionBundle:Acreditacion a1, AcreditacionBundle:EstadoAcreditacion e1
        where e1.codEstadoAcreditacion in (\'AC\',\'AO\')
        and a1.idEstadoAcreditacion=e1.idEstadoAcreditacion
        and a1.idCentroEducativo=ce.idCentroEducativo
    )')
    ->andWhere('not exists (
        select 1
        from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
        where a.fechaInicio<=:fechaRef
        and a.fechaFin>=:fechaRef
        and e.codEstadoAcreditacion in (\'AC\',\'AO\')
        and a.idEstadoAcreditacion=e.idEstadoAcreditacion
        and a.idCentroEducativo=ce.idCentroEducativo
    )')
        ->setParameter('fechaRef',$fechaRef)
        ->getQuery()->getResult();

//por vencer
$porVencer=$em->createQueryBuilder()
    ->select('ce.codCentroEducativo, ce.nbrCentroEducativo')
    ->from('AcreditacionBundle:CentroEducativo', 'ce')
    ->where('exists (
        select 1
        from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
        where a.fechaInicio<=:fechaActual
        and a.fechaFin>=:fechaActual
        and e.codEstadoAcreditacion in (\'AC\',\'AO\')
        and a.idEstadoAcreditacion=e.idEstadoAcreditacion
        and a.idCentroEducativo=ce.idCentroEducativo
    )')
    ->andWhere('not exists (
        select 1
        from AcreditacionBundle:Acreditacion a1, AcreditacionBundle:EstadoAcreditacion e1
        where a1.fechaInicio<=:fechaRef
        and a1.fechaFin>=:fechaRef
        and e1.codEstadoAcreditacion in (\'AC\',\'AO\')
        and a1.idEstadoAcreditacion=e1.idEstadoAcreditacion
        and a1.idCentroEducativo=ce.idCentroEducativo
    )')
        ->setParameter('fechaRef',$fechaRef)
        ->setParameter('fechaActual',new \DateTime()) //hoy
        ->getQuery()->getResult();

//var_dump($porVencer);
*/
        

      

     
        
       $pdfObj->writeHTML($html, true, 0, true, 0);
$pdfObj->lastPage();

        

        $pdfObj->Output("informeCuantitat.pdf", 'I');
    }


}
