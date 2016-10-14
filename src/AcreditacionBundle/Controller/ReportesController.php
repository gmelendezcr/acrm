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
    
    
    
    
    
    
    
    
    
    
    
    
    
public function pruebaAction(Request $request){
    $anio=$request->get('anio');
    $idCentroEducativo=$request->get('idCentroEducativo');
    ///falta enviarle parámetros por POST
    ///falta enviarle parámetros por POST
    ///falta enviarle parámetros por POST
    $anio=2016;
    $anio=2016;
    $anio=2016;
    $idCentroEducativo=2118;
    $idCentroEducativo=2118;
    $idCentroEducativo=2118;

    $em = $this->getDoctrine()->getManager();
    $pdfObj=$this->get("white_october.tcpdf")->create();

    $pdfObj->setHeaderType('newLogoHeader');
    $pdfObj->setFooterType('simpleFooter');
    $pdfObj->startPageGroup();
    $pdfObj->AddPage();
    $pdfObj->MultiCell($pdfObj->getWorkAreaWidth(),$pdfObj->getLineHeight(),'Listado de centros educativos por estado actual',0,'C');
    $pdfObj->newLine();
    $pdfObj->SetFontSize(9);

    $centroEducativo=$em->createQueryBuilder()
    ->distinct()
    ->select('c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
    m.nbrMunicipio, 
    d.nbrDepartamento')
    ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'f')
        ->join('f.idCentroEducativo','c')
        ->join('c.idMunicipio','m')
        ->join('m.idDepartamento','d')
            //->where('f.idCentroEducativo=:idCentroEducativo')
            //->andWhere('f.fechaAplicacion between :fechaIni and :fechaFin')
                //->setParameter('idCentroEducativo',$idCentroEducativo)
                //->setParameter('fechaIni',$anio . '-1-1')
                //->setParameter('fechaFin',$anio . '-12-31')
                ->getQuery()->getResult();

    
    
    $html = '
    
    <style>
   table{
        color: #003300;
        font-family: helvetica;
        font-size: 8pt;
        background-color: #ccffcc;
    }
    table, td, th {
    border: 1px solid #ccc;
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
        <tr bgcolor="#ccc" valign="middle">
            <th width="10%" valign="middle"><strong>Código</strong></th>
            <th width="30%"><strong>Nombre</strong></th>
            <th width="30%"><strong>Ubicación</strong></th>
            <th width="15%"><strong>Fecha inicial</strong></th>
            <th width="15%"><strong>Fecha vencimiento</strong></th>
        </tr>
        ';
 foreach ($centroEducativo as $cd) {
    $html .='<tr>
    <td>'.$cd["codCentroEducativo"].'</td>
    <td>'.$cd["nbrCentroEducativo"].'</td>
    <td>'.$cd["nbrDepartamento"].', '.$cd["nbrMunicipio"].', '.$cd["direccionCentroEducativo"].'</td>
    <td></td>
    <td></td>
    </tr>';


}
    $html .='</table>';
    //var_dump($centroEducativo);
   // foreach ($centroEducativo as $cd) {
     //  var_dump($cd);
       //exit();
    
   /* $pdfObj->dataTable(array(
        array('title' => '','border' => 1,'width' => 25,),
        array('title' => '','border' => 1,),
    ),array(
        array(
            'Centro educativo:',
            $centroEducativo['nbrCentroEducativo'],
        ),
        array(
            'Código:',
        $centroEducativo['codCentroEducativo'],
        ),
        array(
            'Departamento:',
            $centroEducativo['nbrDepartamento'],
        ),
        array(
            'Municipio:',
            $centroEducativo['nbrMunicipio'],
        ),
    ),array(),false);
    */
    //}

        

      

     
        
       $pdfObj->writeHTML($html, true, 0, true, 0);
$pdfObj->lastPage();

        

        $pdfObj->Output("informeCuantitat.pdf", 'I');
    }


}
