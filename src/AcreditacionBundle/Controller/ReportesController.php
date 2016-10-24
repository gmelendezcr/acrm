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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use PHPExcel_IOFactory;

class ReportesController extends Controller{
    
     public function errorAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        
         return $this->render('reportes/error404.html.twig');
        
    }
    
    
    public function ConsultaPublicaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
    
    $criterio_buscar=$request->get('criterio');
    if((isset($criterio_buscar)) && (!empty($criterio_buscar))){
    $criterio="%".$criterio_buscar."%";
    $lista_cedu=$em->createQueryBuilder()
    ->select('
            ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
            d.nbrDepartamento,
            m.nbrMunicipio,
            
           
            case
                when not exists (
                    select 1
                    from AcreditacionBundle:Acreditacion a
                        where a.idCentroEducativo=ce.idCentroEducativo
                ) then \'No evaluado\'
                
                when exists (
                    select 1
                    from AcreditacionBundle:Acreditacion a1, AcreditacionBundle:EstadoAcreditacion e
                        where a1.fechaInicio<=:fechaRef
                            and a1.fechaFin>=:fechaRef
                            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a1.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a1.idCentroEducativo=ce.idCentroEducativo
                ) then \'Acreditado\'
                
                when exists (
                    select 1
                    from AcreditacionBundle:Acreditacion a2, AcreditacionBundle:EstadoAcreditacion e2
                        where a2.fechaInicio<=:fechaRef
                            and a2.fechaFin>=:fechaRef
                            and e2.codEstadoAcreditacion in (\'NA\')
                            and a2.idEstadoAcreditacion=e2.idEstadoAcreditacion
                            and a2.idCentroEducativo=ce.idCentroEducativo    
                    
                ) then \'No acreditado\'
                
                when exists (
                select 1
                    from AcreditacionBundle:Acreditacion a3, AcreditacionBundle:EstadoAcreditacion e3
                        where a3.fechaFin<:fechaRef
                            and e3.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a3.idEstadoAcreditacion=e3.idEstadoAcreditacion
                            and a3.idCentroEducativo=ce.idCentroEducativo
                
                ) then \'Vencido\'

                
                else
                \'\'
            end as estado
        ')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        //->join('ce.acreditaciones','acred' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
        ->where('ce.nbrCentroEducativo like :nbr')
        ->orWhere('ce.codCentroEducativo like :nbr')
            /*->andwhere('exists (
                select 1
                    from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                        where a.fechaInicio<=:fechaRef
                            and a.fechaFin>=:fechaRef
                            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a.idCentroEducativo=ce.idCentroEducativo
            )')*/
        
        ->setParameter('nbr', $criterio)
        ->setParameter('fechaRef', new \DateTime())
        ->getQuery()->getResult();
    }else{
        $lista_cedu=array();
    }
        //var_dump($lista_cedu); 
        
        return $this->render('reportes/reporte.ConsultaPublica.html.twig',
            array(
                'lista_cedu'=>$lista_cedu,
                'criterio'=>$criterio_buscar,
            ));
    }
     public function ConsultaPublicaBuscarAction(Request $request){
    
    $em = $this->getDoctrine()->getManager();
    
    $criterio=$request->get('criterio');
    $criterio="%".$criterio."%";
    $lista_cedu=$em->createQueryBuilder()
    ->select('
            ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
            d.nbrDepartamento,
            m.nbrMunicipio,
            acred.fechaInicio,
            acred.fechaFin,
            est.nbrEstadoAcreditacion
        ')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        ->join('ce.acreditaciones','acred' )
        ->join('acred.idEstadoAcreditacion','est' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
        ->where('ce.nbrCentroEducativo like :nbr')
            ->andwhere('exists (
                select 1
                    from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                        where a.fechaInicio<=:fechaRef
                            and a.fechaFin>=:fechaRef
                            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a.idCentroEducativo=ce.idCentroEducativo
            )')
        
        ->setParameter('nbr', $criterio)
        ->setParameter('fechaRef', new \DateTime())
        ->getQuery()->getResult();
        //var_dump($lista_cedu); 
         
        return $this->render('reportes/reporte.ConsultaPublicaReporte.html.twig',
            array(
                'lista_cedu'=>$lista_cedu
            )
        );
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function cuantitativo_cualitativoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_cedu = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        
        $lista_cedu=$em->createQueryBuilder()
        ->select('
            ce.idCentroEducativo, ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo,
            d.nbrDepartamento,
            m.nbrMunicipio,
            acred.fechaInicio,
            acred.fechaFin,
            est.nbrEstadoAcreditacion
        ')
        ->from('AcreditacionBundle:CentroEducativo', 'ce')
        ->join('ce.acreditaciones','acred' )
        ->join('acred.idEstadoAcreditacion','est' )
        ->join('ce.idMunicipio','m')
        ->join('m.idDepartamento','d')
        //->where('ce.nbrCentroEducativo like :nbr')
            ->andwhere('exists (
                select 1
                    from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                        where a.fechaInicio<=:fechaRef
                            and a.fechaFin>=:fechaRef
                            and e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a.idCentroEducativo=ce.idCentroEducativo
            )')
        //->setParameter('nbr', $criterio)
        ->setParameter('fechaRef', new \DateTime())
        ->getQuery()->getResult();
        
        
        return $this->render('reportes/reporte.CuantitativoCualidativo.html.twig',array(
            'lista_cedu'=>$lista_cedu
            ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function estadoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        //$lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        //$lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        $lista_zona = $em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->findAll();
        $lista_modalidad = $em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->findAll();
        $lista_estado_acred = $em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findAll();
        return $this->render('reportes/reporte.Estado.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            //'lista_departamento'    =>$lista_departamento,
            //'lista_municipio'       =>$lista_municipio,
            'lista_zona'            =>$lista_zona,
            'lista_modalidad'       =>$lista_modalidad,
            'lista_estado_acred'    =>$lista_estado_acred,
        ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function zonaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        //$lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        //$lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        $lista_zona = $em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->findAll();
        $lista_modalidad = $em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->findAll();
        $lista_estado_acred = $em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findAll();
        return $this->render('reportes/reporte.Zona.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            //'lista_departamento'    =>$lista_departamento,
            //'lista_municipio'       =>$lista_municipio,
            'lista_zona'            =>$lista_zona,
            'lista_modalidad'       =>$lista_modalidad,
            'lista_estado_acred'    =>$lista_estado_acred,
        ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function rango_fechaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $lista_tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        //$lista_departamento = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        //$lista_municipio = $em->getRepository('AcreditacionBundle:Municipio')->findAll();
        $lista_zona = $em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->findAll();
        $lista_modalidad = $em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->findAll();
        return $this->render('reportes/reporte.RangoFecha.html.twig',array(
            'lista_tamanno'         =>$lista_tamanno,
            //'lista_departamento'    =>$lista_departamento,
            //'lista_municipio'       =>$lista_municipio,
            'lista_zona'            =>$lista_zona,
            'lista_modalidad'       =>$lista_modalidad,
        ));
    }
    
    
    
    
    
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function form_estado_actual_ceduAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        //$lista_estado_acred = $em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findAll();
         return $this->render('reportes/formReporte.EstadoCEDU.html.twig',array(
            //'lista_estado_acred'         =>$lista_estado_acred
        ));
        
    }
    
    
    
    
    
    
    
    
    
    
/**
* @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
*/
public function pdf_estado_actual_ceduAction(Request $request){
    $estado_acred=$request->get('estado_acred');
    $formato=$request->get('formato');
     $fecha=$request->get('fecha');
     $fechaRef=new \DateTime($fecha);
     
    $em = $this->getDoctrine()->getManager();
    
    if($formato=="pdf"){
    $pdfObj=$this->get("white_october.tcpdf")->create();
    $pdfObj->setHeaderType('newLogoHeader');
    $pdfObj->setFooterType('simpleFooter');
    $pdfObj->startPageGroup();
    $pdfObj->AddPage();
    $pdfObj->MultiCell($pdfObj->getWorkAreaWidth(),$pdfObj->getLineHeight(),'Listado de centros educativos por estado actual',0,'C');
    $pdfObj->newLine();
    $pdfObj->SetFontSize(9);
    }
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

    
    if($formato=="pdf"){
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
    $pdfObj->writeHTML($html, true, 0, true, 0);
    $pdfObj->lastPage();
    $pdfObj->Output("informeCuantitat.pdf", 'I');
    }else{
        
        //Excel
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()
            ->setCreator("")
            ->setLastModifiedBy("")
            //->setTitle("Listado de centros educativos por estado actual")
            ->setSubject("Listado de centros educativos por estado actual")
            ->setDescription("Listado de centros educativos por estado actual")
            ->setKeywords("Listado de centros educativos por estado actual");
        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle('Centros educativos');
        $phpExcelObject->setActiveSheetIndex(0)
            ->mergeCells('B1:F1')
            ->setCellValue('B1', 'Listado de centros educativos por estado actual')
            ->setCellValue('B3', 'Código')
            ->setCellValue('C3', 'Nombre')
            ->setCellValue('D3', 'Ubicación')
            ->setCellValue('E3', 'Fecha inicia')
            ->setCellValue('F3', 'Fecha vencimiento');
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(10);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(35);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(35);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(15);
        $row = 4;
        foreach($lista_cedu as $cd){
            $codigo                 =$cd["codCentroEducativo"];
            $nbr_centro_educativo   =$cd["nbrCentroEducativo"];
            $direccion              =$cd["nbrDepartamento"].','.$cd["nbrMunicipio"].''.$cd["direccionCentroEducativo"];
            $fecha_inicial          =$cd["fechaInicio"]->format("d-m-Y");
            $fecha_vencimiento      =$cd["fechaFin"]->format("d-m-Y");
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B'.$row, $codigo)
                ->setCellValue('C'.$row, $nbr_centro_educativo)
                ->setCellValue('D'.$row, $direccion)
                ->setCellValue('E'.$row, $fecha_inicial)
                ->setCellValue('F'.$row, $fecha_vencimiento);
            $row++;
        }
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Listado de centros educativos por estado actual.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }
    
    
    
    }

    private function informacionGeneralCentro()
    {
        $this->pdfObj->setFilasZebra(true);
        $this->pdfObj->dataTable(array(
                array('title' => '','border' => 1,'width' => 25,),
                array('title' => '','border' => 1,),
            ),array(
                array(
                    'Centro educativo:',
                    $this->centroEducativo['nbrCentroEducativo'],
                ),
                array(
                    'Código:',
                    $this->centroEducativo['codCentroEducativo'],
                ),
                array(
                    'Departamento:',
                    $this->centroEducativo['nbrDepartamento'],
                ),
                array(
                    'Municipio:',
                    $this->centroEducativo['nbrMunicipio'],
                ),
            ),array(),false);
        $this->pdfObj->setFilasZebra(false);
        $this->pdfObj->newLine();
    }

    private function encabezadoCuantitativo($em,$anio,$idCentroEducativo)
    {
        $this->pdfObj->setHeaderType('newLogoHeader');
        $this->pdfObj->setFooterType('simpleFooter');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage();
        $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'RESULTADOS DE LA EVALUACIÓN EXTERNA PARA LA ACREDITACIÓN INSTITUCIONAL DE CENTROS EDUCATIVOS PRIVADOS AÑO ' . $anio,0,'C');
        $this->pdfObj->newLine();
        $this->pdfObj->SetFontSize(9);

        $this->centroEducativo=$em->createQueryBuilder()
            ->distinct()
            ->select('c.codCentroEducativo, c.nbrCentroEducativo, m.nbrMunicipio, d.nbrDepartamento')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('c.idMunicipio','m')
            ->join('m.idDepartamento','d')
            ->where('fce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                    ->getQuery()->getSingleResult();

        $this->informacionGeneralCentro();
    }
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function informeCuantitativoAction(Request $request)
    {
        $anio=$request->get('anno');
        $idCentroEducativo=$request->get('centrosEducativo');
        $versionParaCoordinador=$request->get('versionParaCoordinador');

        $em = $this->getDoctrine()->getManager();
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $this->encabezadoCuantitativo($em,$anio,$idCentroEducativo);

        $puntosPorCriterio=$em->createQueryBuilder()
            ->select('s.idSeccion, s.nbrSeccion, sum(p.ponderacionMaxima)/100 as ponderacionMaxima')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idFormulario','f')
            ->join('f.secciones','s')
            ->join('s.preguntas','p')
            ->where('fce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
            ->andWhere('exists (
                select 1
                from AcreditacionBundle:Pregunta p2
                where p2.ponderacionMaxima is not null
                and p2.idSeccion=s.idSeccion
            )')
            ->groupBy('s.idSeccion, s.nbrSeccion')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                    ->getQuery()->getResult();

        $puntosPorCriterioData=array();
        $tot1=$tot2=$tot3=0;
        foreach ($puntosPorCriterio as $puntoPorCriterio) {
            $ponderacionObtenida=$em->createQueryBuilder()
                ->select('avg(v.ponderacionGanada)/100 as ponderacionGanada')
                ->from('AcreditacionBundle:ViewFormularioPorCentroEducativoSeccionPonderacion', 'v')
                ->join('v.idFormularioPorCentroEducativo','fce')
                ->where('fce.idCentroEducativo=:idCentroEducativo')
                ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                ->andWhere('v.idSeccion=:idSeccion')
                    ->setParameter('idCentroEducativo',$idCentroEducativo)
                    ->setParameter('idSeccion',$puntoPorCriterio['idSeccion'])
                    ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                    ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                        ->getQuery()->getSingleResult();
            if(!$versionParaCoordinador || $puntoPorCriterio['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada']>0){
                $puntosPorCriterioData[]=array(
                    $puntoPorCriterio['nbrSeccion'],
                    number_format(round($puntoPorCriterio['ponderacionMaxima'],2),2),
                    number_format(round($ponderacionObtenida['ponderacionGanada'],2),2),
                    number_format(round($puntoPorCriterio['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada'],2),2),
                );
            }
            $tot1+=round($puntoPorCriterio['ponderacionMaxima'],2);
            $tot2+=round($ponderacionObtenida['ponderacionGanada'],2);
            $tot3+=round($puntoPorCriterio['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada'],2);
        }
        $puntosPorCriterioData[]=array(
            'Resultado de evaluación',
            number_format($tot1,2),
            number_format($tot2,2),
            number_format($tot3,2),
        );
        $this->pdfObj->setColorearTotales(true);
        $this->pdfObj->dataTable(array(
                array('title' => 'CRITERIOS PROMEDIADOS BÁSICA, MEDIA Y PARVULARIA','border' => 1,'width' => 50,),
                array('title' => 'Puntuación global esperada','border' => 1,),
                array('title' => 'Puntuación global obtenida','border' => 1,),
                array('title' => 'Diferencia','border' => 1,),
            ),$puntosPorCriterioData,array());
        $this->pdfObj->newLine();
        $resultado=$tot2;

        $formularios=$em->createQueryBuilder()
            ->select('f.codFormulario, f.nbrFormulario')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idFormulario','f')
            ->where('fce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
            ->orderBy('f.codFormulario')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                ->getQuery()->getResult();

        $primerFormulario=true;
        foreach ($formularios as $formulario) {

            if(!$primerFormulario){
                $this->pdfObj->AddPage();
                $this->informacionGeneralCentro();
            }
            $primerFormulario=false;

            reset($puntosPorCriterio);
            foreach ($puntosPorCriterio as $criterio) {

                $puntosPorIndicador=$em->createQueryBuilder()
                    ->select('i.idIndicador, i.nbrIndicador, sum(p.ponderacionMaxima)/100 as ponderacionMaxima')
                    ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
                    ->join('fce.idFormulario','f')
                    ->join('f.secciones','s')
                    ->join('s.preguntas','p')
                    ->join('p.idIndicador','i')
                    ->where('fce.idCentroEducativo=:idCentroEducativo')
                    ->andWhere('f.codFormulario=:codFormulario')
                    ->andWhere('p.idSeccion=:idSeccion')
                    ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                    ->groupBy('i.idIndicador, i.nbrIndicador')
                        ->setParameter('idCentroEducativo',$idCentroEducativo)
                        ->setParameter('codFormulario',$formulario['codFormulario'])
                        ->setParameter('idSeccion',$criterio['idSeccion'])
                        ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                        ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                            ->getQuery()->getResult();

                $mostrarCriterio=false;
                $tot1=$tot2=$tot3=0;
                $puntosPorIndicadorData=array();
                foreach ($puntosPorIndicador as $puntoPorIndicador) {
                    $ponderacionObtenida=$em->createQueryBuilder()
                        ->select('avg(v.ponderacionGanada)/100 as ponderacionGanada')
                        ->from('AcreditacionBundle:ViewFormularioPorCentroEducativoIndicadorPonderacion', 'v')
                        ->join('v.idFormularioPorCentroEducativo','fce')
                        ->where('fce.idCentroEducativo=:idCentroEducativo')
                        ->andWhere('v.idIndicador=:idIndicador')
                        ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                            ->setParameter('idCentroEducativo',$idCentroEducativo)
                            ->setParameter('idIndicador',$puntoPorIndicador['idIndicador'])
                            ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                            ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                                ->getQuery()->getSingleResult();
                    if(!$versionParaCoordinador || $puntoPorIndicador['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada']>0){
                        $puntosPorIndicadorData[]=array(
                            $puntoPorIndicador['nbrIndicador'],
                            number_format(round($puntoPorIndicador['ponderacionMaxima'],2),2),
                            number_format(round($ponderacionObtenida['ponderacionGanada'],2),2),
                            number_format(round($puntoPorIndicador['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada'],2),2),
                        );
                        $mostrarCriterio=true;
                    }
                    $tot1+=round($puntoPorIndicador['ponderacionMaxima'],2);
                    $tot2+=round($ponderacionObtenida['ponderacionGanada'],2);
                    $tot3+=round($puntoPorIndicador['ponderacionMaxima'] - $ponderacionObtenida['ponderacionGanada'],2);
                }
                $puntosPorIndicadorData[]=array(
                    'Resultado de evaluación',
                    number_format($tot1,2),
                    number_format($tot2,2),
                    number_format($tot3,2),
                );

                if($mostrarCriterio){
                    $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),$criterio['nbrSeccion'] . "\n" . $formulario['nbrFormulario'],0,'C');
                    $this->pdfObj->newLine();

                    $this->pdfObj->dataTable(array(
                            array('title' => 'INDICADORES','border' => 1,'width' => 50,),
                            array('title' => 'Puntuación global esperada','border' => 1,),
                            array('title' => 'Puntuación global obtenida','border' => 1,),
                            array('title' => 'Diferencia','border' => 1,),
                        ),$puntosPorIndicadorData,array());
                    $this->pdfObj->newLine();
                }

            }

            if($versionParaCoordinador){
                $codSeccion='I';
                $observaciones=$em->createQueryBuilder()
                    ->select('r.valorRespuesta')
                    ->from('AcreditacionBundle:FormularioPorCentroEducativo','fce')
                    ->join('fce.idFormulario','f')
                    ->join('fce.respuestasPorFormularioPorCentroEducativo','r')
                    ->join('r.idPregunta','p')
                    ->join('p.idSeccion','s')
                    ->where('s.codSeccion=:codSeccion')
                    ->andwhere('fce.idCentroEducativo=:idCentroEducativo')
                    ->andWhere('f.codFormulario=:codFormulario')
                    ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                        ->setParameter('codSeccion',$codSeccion)
                        ->setParameter('idCentroEducativo',$idCentroEducativo)
                        ->setParameter('codFormulario',$formulario['codFormulario'])
                        ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                        ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                            ->getQuery()->getResult();

                $observacionesArr=array();
                foreach ($observaciones as $observacion) {
                    $observacionStr='';
                    $observacionArr=explode("\n",$observacion['valorRespuesta']);
                    foreach ($observacionArr as $oa) {
                        if($oa){
                            $observacionStr.='• ' . $oa . "\n";
                        }
                    }
                    $observacionesArr[]=array(
                        trim($observacionStr),
                    );
                }

                $this->pdfObj->setColorearTotales(false);
                $this->pdfObj->dataTable(array(
                        array('title' => 'Observaciones','border' => 1,),
                    ),$observacionesArr,array());
                $this->pdfObj->newLine();
            }

        }

        if(!$versionParaCoordinador){
            $margins=$this->pdfObj->getMargins();
            $col1=60;
            $col2=20;
            $this->pdfObj->setX($margins['left']+($this->pdfObj->getWorkAreaWidth()-$col1-$col2)/2);
            $this->pdfObj->MultiCell($col1,2*$this->pdfObj->getLineHeight(),'RESULTADO DE ACREDITACIÓN INSTITUCIONAL',1,'C',
                false,0,'','',true,0,false,true,2*$this->pdfObj->getLineHeight(),'M'
                );
            $this->pdfObj->SetFontSize(14);
            $this->pdfObj->MultiCell($col2,2*$this->pdfObj->getLineHeight(),$resultado,1,'C',
                false,1,'','',true,0,false,true,2*$this->pdfObj->getLineHeight(),'M'
                );
            $this->pdfObj->newLine(2);

            $this->pdfObj->SetFontSize(9);
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),$this->pdfObj->getAutoridad('directorGestion'),0,'C',false,0);
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),$this->pdfObj->getAutoridad('jefeAcreditacion'),0,'C');
        }

        $this->pdfObj->Output("informeCuantitativo-" . $this->centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function informeCualitativoAction(Request $request)
    {
        $anio=$request->get('anno');
        $idCentroEducativo=$request->get('centrosEducativo');

        $em = $this->getDoctrine()->getManager();
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $this->encabezadoCuantitativo($em,$anio,$idCentroEducativo);
        $this->pdfObj->setFooterType('imageFooter');

        $formularios=$em->createQueryBuilder()
            ->select('f.codFormulario, f.nbrFormulario')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idFormulario','f')
            ->where('fce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
            ->orderBy('f.codFormulario')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                ->getQuery()->getResult();

        $primerFormulario=true;
        foreach ($formularios as $formulario) {

            if(!$primerFormulario){
                $this->pdfObj->AddPage();
                $this->informacionGeneralCentro();
            }
            $primerFormulario=false;

            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'OBSERVACIONES PARA ELABORAR EL PLAN DE MEJORAMIENTO INSTITUCIONAL' . "\n" . $formulario['nbrFormulario'],0,'C');
            $this->pdfObj->newLine();

            $criterios=$em->createQueryBuilder()
                ->select('s.nbrSeccion, sfce.observacion')
                ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
                ->join('fce.idFormulario','f')
                ->join('f.secciones','s')
                ->leftJoin('s.seccionesPorFormularioPorCentroEducativo','sfce')
                ->where('fce.idCentroEducativo=:idCentroEducativo')
                ->andWhere('f.codFormulario=:codFormulario')
                ->andWhere('fce.fechaAplicacion between :fechaIni and :fechaFin')
                ->andWhere('exists (
                    select 1
                    from AcreditacionBundle:Pregunta p
                    where p.ponderacionMaxima is not null
                    and p.idSeccion=s.idSeccion
                )')
                ->orderBy('s.codSeccion')
                    ->setParameter('idCentroEducativo',$idCentroEducativo)
                    ->setParameter('codFormulario',$formulario['codFormulario'])
                    ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                    ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                    ->getQuery()->getResult();

            $arrCriterios=array();
            foreach ($criterios as $criterio) {
                if($criterio['observacion']){
                    $observacion='';
                    $observacionArr=explode("\n",$criterio['observacion']);
                    foreach ($observacionArr as $oa) {
                        if($oa){
                            $observacion.='• ' . $oa . "\n";
                        }
                    }
                    $observacion=trim($observacion);
                }
                else{
                    $observacion='Sin observaciones';
                }
                $arrCriterios[]=array(
                    $criterio['nbrSeccion'],
                    $observacion,
                );
            }

            $this->pdfObj->setFilasZebra(true);
            $this->pdfObj->dataTable(array(
                    array('title' => 'CRITERIOS','border' => 1,'width' => 50,'align' => 'C'),
                    array('title' => 'OBSERVACIONES','border' => 1,),
                ),$arrCriterios,array());
            $this->pdfObj->setFilasZebra(true);
            $this->pdfObj->newLine();

        }

        $this->pdfObj->Output("informeCualitativo-" . $this->centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function diplomaAction(Request $request)
    {
        $anio=$request->get('anio');
        $idCentroEducativo=$request->get('idCentroEducativo');

        $em = $this->getDoctrine()->getManager();
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $centroEducativo=$em->createQueryBuilder()
            ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, a.fechaRegistro, a.fechaInicio, a.fechaFin, e.nbrEstadoAcreditacion')
            ->from('AcreditacionBundle:CentroEducativo', 'ce')
            ->join('ce.acreditaciones','a')
            ->join('a.idEstadoAcreditacion','e')
            ->where('ce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('a.fechaInicio between :fechaIni and :fechaFin')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                ->getQuery()->getSingleResult();

        $this->pdfObj->setHeaderType('diplomaHeader');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage('L');

        $centrado=($this->pdfObj->GetPageWidth()/2)-125;

        $this->pdfObj->SetFontSize(18);
        $this->pdfObj->SetX($centrado);
        $this->pdfObj->SetY($this->pdfObj->GetY()+25);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Por cuanto el:', 0, 'C');

        $this->pdfObj->SetFontSize(28);
        $this->pdfObj->SetX($centrado);
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), $centroEducativo['nbrCentroEducativo'], 0, 'C');
        $this->pdfObj->SetXY($centrado,$this->pdfObj->GetY()+2);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'CÓDIGO ' . $centroEducativo['codCentroEducativo'], 0, 'C');

        $this->pdfObj->SetFontSize(14);
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Ha cumplido con los requisitos establecidos en el marco legal del Sistema de Acreditación.', 0, 'C');
        $this->pdfObj->SetY($this->pdfObj->GetY()+9);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Por tanto otorga al:', 0, 'C');

        $this->pdfObj->SetFontSize(18);
        $this->pdfObj->SetY($this->pdfObj->GetY()+4);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), $centroEducativo['nbrCentroEducativo'], 0, 'C');
        
        $this->pdfObj->SetFontSize(14);
        $this->pdfObj->SetY($this->pdfObj->GetY()+11);
        $this->pdfObj->writeHTMLCell(250, $this->pdfObj->getLineHeight(),$this->pdfObj->GetX(),$this->pdfObj->GetY(), 'La Presente <strong>Acreditación Institucional</strong> que lo reconoce como <strong>CENTRO EDUCATIVO PRIVADO ' . strtoupper($centroEducativo['nbrEstadoAcreditacion']) . '</strong> para el período ' . $this->pdfObj->dateLongFormat($centroEducativo['fechaInicio']->format('d/m/Y'),false,true) . ' a ' . $this->pdfObj->dateLongFormat($centroEducativo['fechaFin']->format('d/m/Y'),false,true) . '.', 0, 0, false, true, 'C');

        $this->pdfObj->SetFontSize(12);
        $this->pdfObj->SetY($this->pdfObj->GetY()+23);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'San Salvador, ' . $this->pdfObj->dateLongFormat($centroEducativo['fechaRegistro']->format('d/m/Y'),false,false), 0, 'C');

        $x=$this->pdfObj->GetX()-15;
        $y=$this->pdfObj->GetY()+20;
        $this->pdfObj->SetXY($x,$y);
$this->pdfObj->setTextShadow(array('enabled' => true, 'depth_w' => 0.4, 'depth_h' => 0.4, 'color' => array(255,255,255)));
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->pdfObj->getAutoridad('ministro','ST'), 0, 'C');
        $this->pdfObj->SetXY($x+92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->pdfObj->getAutoridad('viceMinistro','ST'), 0, 'C');
        $this->pdfObj->SetXY($x+2*92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->pdfObj->getAutoridad('directorGestion','ST'), 0, 'C');

        $this->pdfObj->Output("diploma-" . $centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');

    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function noAcreditadoAction(Request $request)
    {
        $anio=$request->get('anio');
        $idCentroEducativo=$request->get('idCentroEducativo');

        $em = $this->getDoctrine()->getManager();
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $centroEducativo=$em->createQueryBuilder()
            ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo, m.nbrMunicipio, d.nbrDepartamento')
            ->from('AcreditacionBundle:CentroEducativo', 'ce')
            ->join('ce.idMunicipio','m')
            ->join('m.idDepartamento','d')
            ->join('ce.acreditaciones','a')
            ->join('a.idEstadoAcreditacion','e')
            ->where('ce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('a.fechaInicio between :fechaIni and :fechaFin')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'))
                ->getQuery()->getSingleResult();

        $this->pdfObj->setHeaderType('newLogoHeader');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage();

        $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'DIRECCIÓN NACIONAL DE GESTIÓN EDUCATIVA
GERENCIA DE ACREDITACIÓN INSTITUCIONAL Y PRESUPUESTO ESCOLAR
MINISTERIO DE EDUCACIÓN',0,'C');
        $this->pdfObj->newLine();

        $this->pdfObj->SetFont(null,'B');
        $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'CONSIDERANDO:',0,'L');
        $this->pdfObj->SetFont(null,'');
        $this->pdfObj->newLine();

        $this->pdfObj->writeHTMLCell($this->pdfObj->getWorkAreaWidth()+10, $this->pdfObj->getLineHeight(),$this->pdfObj->GetX()-10,$this->pdfObj->GetY(), '
            <ol type="I">
                <li align="justified">Que de conformidad al Art. 57 de La Constitución de la República, los centros de enseñanza Privados están sujetos a reglamentación e inspección del Estado; en consecuencia, compete al Ministerio de Educación, según el Art.38 literales 8 y 27 del  Reglamento Interno del Órgano Ejecutivo, controlar y supervisar los centros Oficiales y Privados de educación, así como regular y supervisar su creación, funcionamiento y nominación;</li>
                <br>
                <li align="justified">En relación a lo anterior y lo dispuesto en el Art. 80 de La Ley General de Educación, compete a este Ministerio establecer los lineamientos de creación y funcionamiento de los Centros Privados de Educación, en cuanto a su Organización Académica y Administrativa, Recursos Físicos y Financieros y el Personal Docente Calificado para el buen funcionamiento de los mismos;</li>
                <br>
                <li align="justified">Que conforme a lo establecido en el Instructivo y Manual Para la Acreditación de Centros Educativos Privados, los centros que cumplan con los requisitos legales y alcancen una calificación menor de 6.5 en el proceso de evaluación, se calificaran como Centros Educativos Privados No Acreditados, otorgándoles un permiso transitorio de dos años, periodo por el cual deberán superar las observaciones planteadas.</li>
                <br>
                <li align="justified">Por lo tanto el ' . $centroEducativo['nbrCentroEducativo'] . ', con código de infraestructura número veinte mil ciento noventa y nueve, ubicada en ' . $centroEducativo['direccionCentroEducativo'] . ', Municipio de ' . $centroEducativo['nbrMunicipio'] . ', Departamento de ' . $centroEducativo['nbrDepartamento'] . '. En los noventa días posteriores a la entrega de resultados, deberán presentar un plan de mejoramiento institucional, en el que detallen las áreas a mejorar, actividades a realizar, recursos resultados esperados y tiempo de ejecución.</li>
                <br>
                <li align="justified">Si transcurrido el permiso transitorio de dos años, no superan las observaciones planteadas, el Ministerio de Educación iniciará el proceso legal que establece el artículo 101 de la Ley General de Educación.</li>
            </ol>', 0, 1, false, true, 'L');
        $this->pdfObj->newLine(3);

        $this->pdfObj->MultiCell(80,$this->pdfObj->getLineHeight(),$this->pdfObj->getAutoridad('directorGestion'),'T','L');

        $this->pdfObj->Output("noAcreditado-" . $centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function estadisticoGeneralAction(Request $request)
    {
        $anio=$request->get('anno');
        $idZonaCentroEducativo=$request->get('zona');
        $idTamannoCentroEducativo=$request->get('tamanno');
        $idModalidadCentroEducativo=$request->get('fines');
        $idEstadoAcreditacion=$request->get('estado');
        $idDepartamento=$request->get('departamento');
        $idMunicipio=$request->get('municipio');
        $listado=($request->get('t_reporte')=='listado'?1:0);

        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $this->pdfObj->setHeaderType('newLogoHeader');
        $this->pdfObj->setFooterType('simpleFooter');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage();



        $conteosPorAnioEstadoQuery=$em->createQueryBuilder();
        if(!$listado){
            $conteosPorAnioEstadoQuery
                ->select('YEAR(a.fechaRegistro) as anio, e.codEstadoAcreditacion as codigo, count(1) as cantidad');
        }
        else{
            $conteosPorAnioEstadoQuery
                ->select('YEAR(a.fechaRegistro) as anio, e.codEstadoAcreditacion, e.nbrEstadoAcreditacion, ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo');
        }
        $conteosPorAnioEstadoQuery
            ->from('AcreditacionBundle:Acreditacion','a')
            ->join('a.idEstadoAcreditacion','e')
            ->join('a.idCentroEducativo','ce')
            ->join('ce.idMunicipio','m')
            ->where('1=1');
        if($anio){
            $conteosPorAnioEstadoQuery
                ->andWhere('a.fechaInicio between :fechaIni and :fechaFin')
                    ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                    ->setParameter('fechaFin',new \DateTime($anio . '-12-31'));
        }
        if($idZonaCentroEducativo){
            $conteosPorAnioEstadoQuery
                ->andWhere('ce.idZonaCentroEducativo=:idZonaCentroEducativo')
                    ->setParameter('idZonaCentroEducativo',$idZonaCentroEducativo);
        }
        if($idTamannoCentroEducativo){
            $conteosPorAnioEstadoQuery
                ->andWhere('ce.idTamannoCentroEducativo=:idTamannoCentroEducativo')
                    ->setParameter('idTamannoCentroEducativo',$idTamannoCentroEducativo);
        }
        if($idModalidadCentroEducativo){
            $conteosPorAnioEstadoQuery
                ->andWhere('ce.idModalidadCentroEducativo=:idModalidadCentroEducativo')
                    ->setParameter('idModalidadCentroEducativo',$idModalidadCentroEducativo);
        }
        if($idEstadoAcreditacion){
            $conteosPorAnioEstadoQuery
                ->andWhere('e.idEstadoAcreditacion=:idEstadoAcreditacion')
                    ->setParameter('idEstadoAcreditacion',$idEstadoAcreditacion);
        }
        if($idDepartamento){
            $conteosPorAnioEstadoQuery
                ->andWhere('m.idDepartamento=:idDepartamento')
                    ->setParameter('idDepartamento',$idDepartamento);
        }
        if($idMunicipio){
            $conteosPorAnioEstadoQuery
                ->andWhere('m.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
        }
        if(!$listado){
            $conteosPorAnioEstadoQuery
                ->groupBy('anio, e.codEstadoAcreditacion')
                ->orderBy('anio');
        }
        else{
            $conteosPorAnioEstadoQuery
                ->orderBy('anio, e.idEstadoAcreditacion, ce.codCentroEducativo');
        }
        $porAnioEstado=$conteosPorAnioEstadoQuery
            ->getQuery()->getResult();



        $filtrosNoEvaluados='';
        $noEvaluadosQuery=$em->createQueryBuilder();
        if(!$listado){
            $noEvaluadosQuery
                ->select('count(1) as cantidad');
        }
        else{
            $noEvaluadosQuery
                ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo');
        }
        $noEvaluadosQuery
            ->from('AcreditacionBundle:CentroEducativo','ce')
            ->join('ce.idMunicipio','m')
            ->where('1=1');
        if($anio){
            $filtrosNoEvaluados.='and a.fechaInicio between :fechaIni and :fechaFin';
            $noEvaluadosQuery
                ->setParameter('fechaIni',new \DateTime($anio . '-1-1'))
                ->setParameter('fechaFin',new \DateTime($anio . '-12-31'));
        }
        if($idZonaCentroEducativo){
            $noEvaluadosQuery
                ->andWhere('ce.idZonaCentroEducativo=:idZonaCentroEducativo')
                    ->setParameter('idZonaCentroEducativo',$idZonaCentroEducativo);
        }
        if($idTamannoCentroEducativo){
            $noEvaluadosQuery
                ->andWhere('ce.idTamannoCentroEducativo=:idTamannoCentroEducativo')
                    ->setParameter('idTamannoCentroEducativo',$idTamannoCentroEducativo);
        }
        if($idModalidadCentroEducativo){
            $noEvaluadosQuery
                ->andWhere('ce.idModalidadCentroEducativo=:idModalidadCentroEducativo')
                    ->setParameter('idModalidadCentroEducativo',$idModalidadCentroEducativo);
        }
        if($idEstadoAcreditacion){
        }
        if($idDepartamento){
            $noEvaluadosQuery
                ->andWhere('m.idDepartamento=:idDepartamento')
                    ->setParameter('idDepartamento',$idDepartamento);
        }
        if($idMunicipio){
            $noEvaluadosQuery
                ->andWhere('m.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
        }
        $noEvaluadosQuery
            ->andWhere('not exists (
                select 1
                from AcreditacionBundle:Acreditacion a
                where 1=1
                ' . $filtrosNoEvaluados . '
                and a.idCentroEducativo=ce.idCentroEducativo
            )');
        if(!$listado){
            $noEvaluados=$noEvaluadosQuery
                ->getQuery()->getSingleScalarResult();
        }
        else{
            $noEvaluados=$noEvaluadosQuery
                ->orderBy('ce.codCentroEducativo')
                    ->getQuery()->getResult();
        }

        if(!$listado){
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'ESTADÍSTICO GENERAL DE EVALUACIÓN',0,'C');
            $this->pdfObj->newLine();

            $estados=$em->createQueryBuilder()
                ->select('e.codEstadoAcreditacion, e.nbrEstadoAcreditacion')
                ->from('AcreditacionBundle:EstadoAcreditacion','e')
                ->orderBy('e.idEstadoAcreditacion')
                    ->getQuery()->getResult();
            $this->pdfObj->crossTab($porAnioEstado,'anio','Año',$estados,'codEstadoAcreditacion','nbrEstadoAcreditacion');

            $this->pdfObj->dataTable(array(
                array('title' => 'No evaluados','border' => 1,'align' => 'R',),
            ),array(
                array($noEvaluados),
            ));
        }
        else{
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'LISTADO GENERAL DE EVALUACIÓN POR ESTADO',0,'C');
            $this->pdfObj->newLine();

            $columnArr=array(
                array('title' => 'Código','border' => 1,'width' => 12,),
                array('title' => 'Nombre','border' => 1,),
                array('title' => 'Dirección','border' => 1,),
            );

            $reporteArr=array();
            $codEstadoAcreditacionAnt='';
            foreach ($porAnioEstado as $value) {

                if($value['codEstadoAcreditacion']!=$codEstadoAcreditacionAnt){
                    if(count($reporteArr)>0){
                        $this->pdfObj->dataTable($columnArr,$reporteArr);
                        $this->pdfObj->newLine();
                        $reporteArr=array();
                    }
                    $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'Año ' . $value['anio'] . ' - ' . $value['nbrEstadoAcreditacion'],0,'L');
                    $this->pdfObj->newLine();
                }

                $reporteArr[]=array(
                    $value['codCentroEducativo'],
                    $value['nbrCentroEducativo'],
                    $value['direccionCentroEducativo'],
                );

                $codEstadoAcreditacionAnt=$value['codEstadoAcreditacion'];
            }
            if(count($reporteArr)>0){
                $this->pdfObj->dataTable($columnArr,$reporteArr);
                $this->pdfObj->newLine();
            }

            $reporteArr=array();
            foreach ($noEvaluados as $value) {
                $reporteArr[]=array(
                    $value['codCentroEducativo'],
                    $value['nbrCentroEducativo'],
                    $value['direccionCentroEducativo'],
                );
            }
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'No evaluados',0,'L');
            $this->pdfObj->newLine();
            $this->pdfObj->dataTable($columnArr,$reporteArr);

        }

        $this->pdfObj->Output((!$listado?'estadisticoGeneral':'listadoGeneral') . ".pdf", 'I');
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function promedioCriterioIndicadorAction(Request $request)
    {
        $anioIni=$request->get('anno');
        $anioFin=$request->get('anno_rango');
        $idZonaCentroEducativo=$request->get('zona');
        $idTamannoCentroEducativo=$request->get('tamanno');
        $idDepartamento=$request->get('departamento');
        $idMunicipio=$request->get('municipio');
        $idModalidadCentroEducativo=$request->get('fines');
        $tipoReporte=$request->get('t_reporte');
        $formato=$request->get('formato');
        $formato_tipo="pdf";
        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $em->getConfiguration()
            ->addCustomDatetimeFunction('ROUND', 'AcreditacionBundle\DQL\RoundFunction');
        
        if($formato==$formato_tipo){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();
            $this->pdfObj->setHeaderType('newLogoHeader');
            $this->pdfObj->setFooterType('simpleFooter');
            $this->pdfObj->startPageGroup();
        }

        if($tipoReporte=='por_criterio'){
            if($formato==$formato_tipo){
                $this->pdfObj->AddPage();
            }

            $promediosQuery=$em->createQueryBuilder()
                ->select('YEAR(fc.fechaAplicacion) as anio, s.idSeccion as codigo, ROUND(AVG(v.ponderacionGanada/100),2) as cantidad')
                ->from('AcreditacionBundle:ViewFormularioPorCentroEducativoSeccionPonderacion','v')
                ->join('v.idFormularioPorCentroEducativo','fc')
                ->join('fc.idCentroEducativo','ce')
                ->join('ce.idMunicipio','m')
                ->join('m.idDepartamento','d')
                ->join('v.idSeccion','s')
                ->groupBy('anio, s.idSeccion');

        }
        elseif($tipoReporte=='por_indicador'){
            if($formato==$formato_tipo){
                $this->pdfObj->AddPage('L');
            }

            $promediosQuery=$em->createQueryBuilder()
                ->select('YEAR(fc.fechaAplicacion) as anio, i.idIndicador as codigo, ROUND(AVG(v.ponderacionGanada/100),2) as cantidad')
                ->from('AcreditacionBundle:ViewFormularioPorCentroEducativoIndicadorPonderacion','v')
                ->join('v.idFormularioPorCentroEducativo','fc')
                ->join('fc.idCentroEducativo','ce')
                ->join('ce.idMunicipio','m')
                ->join('m.idDepartamento','d')
                ->join('v.idIndicador','i')
                ->groupBy('anio, i.idIndicador');

        }

        if($anioIni){
            $promediosQuery
                ->andWhere('fc.fechaAplicacion>=:fechaIni')
                    ->setParameter('fechaIni',new \DateTime($anioIni . '-1-1'));
        }
        if($anioFin){
            $promediosQuery
                ->andWhere('fc.fechaAplicacion<=:fechaFin')
                    ->setParameter('fechaFin',new \DateTime($anioFin . '-12-31'));
        }
        if($idZonaCentroEducativo){
            $promediosQuery
                ->andWhere('ce.idZonaCentroEducativo=:idZonaCentroEducativo')
                    ->setParameter('idZonaCentroEducativo',$idZonaCentroEducativo);
        }
        if($idTamannoCentroEducativo){
            $promediosQuery
                ->andWhere('ce.idTamannoCentroEducativo=:idTamannoCentroEducativo')
                    ->setParameter('idTamannoCentroEducativo',$idTamannoCentroEducativo);
        }
        if($idDepartamento){
            $promediosQuery
                ->andWhere('d.idDepartamento=:idDepartamento')
                    ->setParameter('idDepartamento',$idDepartamento);
        }
        if($idMunicipio){
            $promediosQuery
                ->andWhere('m.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
        }
        if($idModalidadCentroEducativo){
            $promediosQuery
                ->andWhere('ce.idModalidadCentroEducativo=:idModalidadCentroEducativo')
                    ->setParameter('idModalidadCentroEducativo',$idModalidadCentroEducativo);
        }

        $promedios=$promediosQuery
            ->getQuery()->getResult();
   
        if($tipoReporte=='por_criterio'){

            $criterios=$em->createQueryBuilder()
                ->select('s.idSeccion, s.nbrSeccion')
                ->from('AcreditacionBundle:Seccion','s')
                ->where('exists (
                    select 1
                    from AcreditacionBundle:Pregunta p
                    where p.ponderacionMaxima is not null
                    and p.idSeccion=s.idSeccion
                )')
                    ->getQuery()->getResult();
                   
            if($formato==$formato_tipo){
                $this->pdfObj->SetFontSize(12);
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'PROMEDIOS POR CRITERIO',0,'C');
                $this->pdfObj->newLine();
                $this->pdfObj->SetFontSize(7);
                $this->pdfObj->crossTab($promedios,'anio','Año',$criterios,'idSeccion','nbrSeccion',2);
            }else{
                
            //Inicia excel
            
           
            
            $excel_titulo="PROMEDIOS POR CRITERIO";
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()
            ->setCreator("")
            ->setLastModifiedBy("")
            //->setTitle("Listado de centros educativos por estado actual")
            ->setSubject("Listado de centros educativos por estado actual")
            ->setDescription("Listado de centros educativos por estado actual")
            ->setKeywords("Listado de centros educativos por estado actual");
            $phpExcelObject->setActiveSheetIndex(0);
            //$phpExcelObject->getActiveSheet()->setTitle('Centros educativos');
            $contar="67";
            $phpExcelObject->setActiveSheetIndex(0)
            ->mergeCells('B1:D1')
            ->setCellValue('B1', $excel_titulo)
            ->setCellValue('B3', "Año");
            foreach($criterios as $criterio){
                $letra=chr($contar);
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue(''.$letra.'3', $criterio['nbrSeccion'])
                    ->getColumnDimension($letra)
                        ->setWidth(8);
                $contar++;
            }
        /*$phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(20);*/
        $row = 4;
        $suma1="0";
        $suma2="0";
        
       
            
        
            
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            ''.$tipoReporte.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;

        //Fin excel
    
                
                
                
                
                
                
            }
            
            
            
            
            
            
            
            
            
            
            
            
            
        }
        elseif($tipoReporte=='por_indicador'){

            $indicadores=$em->createQueryBuilder()
                ->select('i.idIndicador, i.nbrIndicador')
                ->from('AcreditacionBundle:Indicador','i')
                    ->getQuery()->getResult();
            $this->pdfObj->SetFontSize(12);
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'PROMEDIOS POR INDICADOR',0,'C');
            $this->pdfObj->newLine();
            $this->pdfObj->SetFontSize(6);
            $this->pdfObj->crossTab($promedios,'anio','Año',$indicadores,'idIndicador','nbrIndicador',2);

        }
        if($formato==$formato_tipo){
            $this->pdfObj->Output("promedio" . str_replace(' ','',ucwords(str_replace('_',' ',$tipoReporte))) . ".pdf", 'I');
        }else{
        
        //Inicia excel
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()
            ->setCreator("")
            ->setLastModifiedBy("")
            //->setTitle("Listado de centros educativos por estado actual")
            ->setSubject("Listado de centros educativos por estado actual")
            ->setDescription("Listado de centros educativos por estado actual")
            ->setKeywords("Listado de centros educativos por estado actual");
            $phpExcelObject->setActiveSheetIndex(0);
            //$phpExcelObject->getActiveSheet()->setTitle('Centros educativos');
            $phpExcelObject->setActiveSheetIndex(0)
            ->mergeCells('B1:D1')
            ->setCellValue('B1', $excel_titulo)
            ->setCellValue('B3', "Departamento")
            ->setCellValue('C3', $texto1)
            ->setCellValue('D3', $texto2);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(20);
        $row = 4;
        $suma1="0";
        $suma2="0";
        
        foreach($conteos as $conteo){
            $nbrDepartamento        =$conteo["nbrDepartamento"];
            if($tipoReporte=="colegios_por_departamento"){
                $var_dato1        =$conteo["cantidadColegios"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalAlumnos"];
                $suma2            =$suma2+$var_dato2;

            }elseif($tipoReporte=="docentes_por_departamento"){
                $var_dato1        =$conteo["totalDocentesMasculinos"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalDocentesFemeninos"];
                $suma2            =$suma2+$var_dato2;
            }else{
                //$cantidad1='totalSinFines';
                //$cantidad2='totalConFines';
                $var_dato1        =$conteo["totalSinFines"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalConFines"];
                $suma2            =$suma2+$var_dato2;
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B'.$row, $nbrDepartamento)
                ->setCellValue('C'.$row, $var_dato1)
                ->setCellValue('D'.$row, $var_dato2);
                $row++;
        }
        
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('B'.$row, "Totales")
            ->setCellValue('C'.$row, $suma1)
            ->setCellValue('D'.$row, $suma2);
            
        
            
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            ''.$tipoReporte.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;

        //Fin excel
        
        }
        
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function porDepartamentoAction (Request $request)
    {
        $idZonaCentroEducativo=$request->get('zona');
        $idTamannoCentroEducativo=$request->get('tamanno');
        $idModalidadCentroEducativo=$request->get('fines');
        $idDepartamento=$request->get('departamento');
        $idMunicipio=$request->get('municipio');
        $tipoReporte=$request->get('t_reporte');
        $formato=$request->get('formato');
        $formato_tipo="pdf";
        $em = $this->getDoctrine()->getManager();
        if($formato==$formato_tipo){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();
            $this->pdfObj->setHeaderType('newLogoHeader');
            $this->pdfObj->setFooterType('simpleFooter');
            $this->pdfObj->startPageGroup();
            $this->pdfObj->AddPage();
        }

        if($tipoReporte=='colegios_por_departamento'){
            if($formato==$formato_tipo){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'CANTIDAD DE COLEGIOS Y ALUMNOS POR DEPARTAMENTO',0,'C');
            }else{
                $excel_titulo="CANTIDAD DE COLEGIOS Y ALUMNOS POR DEPARTAMENTO";
            }
            $conteosQuery=$em->createQueryBuilder()
                ->select('d.codDepartamento, d.nbrDepartamento, COUNT(1) as cantidadColegios, SUM(ce.totalAlumnos) as totalAlumnos')
                ->from('AcreditacionBundle:CentroEducativo','ce')
                ->join('ce.idMunicipio','m')
                ->join('m.idDepartamento','d')
                ->groupBy('d.codDepartamento, d.nbrDepartamento')
                ->orderBy('d.codDepartamento');
        }
        elseif($tipoReporte=='docentes_por_departamento'){
            if($formato==$formato_tipo){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'CANTIDAD DE DOCENTES POR SEXO POR DEPARTAMENTO',0,'C');
            }else{
                $excel_titulo="CANTIDAD DE DOCENTES POR SEXO POR DEPARTAMENTO";
            }
            $conteosQuery=$em->createQueryBuilder()
                ->select('d.codDepartamento, d.nbrDepartamento, SUM(ce.totalDocentesMasculinos) as totalDocentesMasculinos, SUM(ce.totalDocentesFemeninos) as totalDocentesFemeninos')
                ->from('AcreditacionBundle:CentroEducativo','ce')
                ->join('ce.idMunicipio','m')
                ->join('m.idDepartamento','d')
                ->groupBy('d.codDepartamento, d.nbrDepartamento')
                ->orderBy('d.codDepartamento');
        }
        elseif($tipoReporte=='modalidad_por_departamento'){
            if($formato==$formato_tipo){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'CANTIDAD DE COLEGIOS SIN FINES O CON FINES DE LUCRO',0,'C');
            }else{
                $excel_titulo="CANTIDAD DE COLEGIOS SIN FINES O CON FINES DE LUCRO";
            }
            $conteosQuery=$em->createQueryBuilder()
                ->select('d.codDepartamento, d.nbrDepartamento, SUM(
                    case
                        when mo.codModalidadCentroEducativo=\'SF\' then 1
                        else 0
                    end
                ) as totalSinFines, SUM(
                    case
                        when mo.codModalidadCentroEducativo=\'CF\' then 1
                        else 0
                    end
                ) as totalConFines')
                ->from('AcreditacionBundle:CentroEducativo','ce')
                ->join('ce.idMunicipio','m')
                ->join('m.idDepartamento','d')
                ->join('ce.idModalidadCentroEducativo','mo')
                ->groupBy('d.codDepartamento, d.nbrDepartamento')
                ->orderBy('d.codDepartamento');
        }
        if($formato==$formato_tipo){
        $this->pdfObj->newLine();
        }

        if($idZonaCentroEducativo){
            $conteosQuery
                ->andWhere('ce.idZonaCentroEducativo=:idZonaCentroEducativo')
                    ->setParameter('idZonaCentroEducativo',$idZonaCentroEducativo);
        }
        if($idTamannoCentroEducativo){
            $conteosQuery
                ->andWhere('ce.idTamannoCentroEducativo=:idTamannoCentroEducativo')
                    ->setParameter('idTamannoCentroEducativo',$idTamannoCentroEducativo);
        }
        if($idModalidadCentroEducativo){
            $conteosQuery
                ->andWhere('ce.idModalidadCentroEducativo=:idModalidadCentroEducativo')
                    ->setParameter('idModalidadCentroEducativo',$idModalidadCentroEducativo);
        }
        if($idDepartamento){
            $conteosQuery
                ->andWhere('d.idDepartamento=:idDepartamento')
                    ->setParameter('idDepartamento',$idDepartamento);
        }
        if($idMunicipio){
            $conteosQuery
                ->andWhere('m.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
        }

        $conteos=$conteosQuery
            ->getQuery()->getResult();

        if($tipoReporte=='colegios_por_departamento'){
            $codigo1='CC';
            $codigo2='TA';
            $cantidad1='cantidadColegios';
            $cantidad2='totalAlumnos';
            $texto1='Cantidad de colegios';
            $texto2='Cantidad de alumnos';
        }
        elseif($tipoReporte=='docentes_por_departamento'){
            $codigo1='DM';
            $codigo2='DF';
            $cantidad1='totalDocentesMasculinos';
            $cantidad2='totalDocentesFemeninos';
            $texto1='Docentes masculinos';
            $texto2='Docentes femeninos';
        }
        elseif($tipoReporte=='modalidad_por_departamento'){
            $codigo1='SF';
            $codigo2='CF';
            $cantidad1='totalSinFines';
            $cantidad2='totalConFines';
            $texto1='Sin fines de lucro';
            $texto2='Con fines de lucro';
        }

        $reporteArr=array();
        foreach ($conteos as $conteo) {
            $reporteArr[]=array(
                'nbrDepartamento' => $conteo['nbrDepartamento'],
                'codigo' => $codigo1,
                'cantidad' => $conteo[$cantidad1],
            );
            $reporteArr[]=array(
                'nbrDepartamento' => $conteo['nbrDepartamento'],
                'codigo' => $codigo2,
                'cantidad' => $conteo[$cantidad2],
            );
        }
        if($formato==$formato_tipo){
            $this->pdfObj->crossTab($reporteArr,'nbrDepartamento','Departamento',array(
                array('cod' => $codigo1, 'nbr' => $texto1),
                array('cod' => $codigo2, 'nbr' => $texto2),
            ),'cod','nbr',0,'C');
            $this->pdfObj->Output("reporte" . str_replace(' ','',ucwords(str_replace('_',' ',$tipoReporte))) . ".pdf", 'I');
        }else{
            //Excel
            //var_dump($reporteArr);
            //die();
            
            
            
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()
            ->setCreator("")
            ->setLastModifiedBy("")
            //->setTitle("Listado de centros educativos por estado actual")
            ->setSubject("Listado de centros educativos por estado actual")
            ->setDescription("Listado de centros educativos por estado actual")
            ->setKeywords("Listado de centros educativos por estado actual");
            $phpExcelObject->setActiveSheetIndex(0);
            //$phpExcelObject->getActiveSheet()->setTitle('Centros educativos');
            $phpExcelObject->setActiveSheetIndex(0)
            ->mergeCells('B1:D1')
            ->setCellValue('B1', $excel_titulo)
            ->setCellValue('B3', "Departamento")
            ->setCellValue('C3', $texto1)
            ->setCellValue('D3', $texto2);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(40);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(20);
        $row = 4;
        $suma1="0";
        $suma2="0";
        
        foreach($conteos as $conteo){
            $nbrDepartamento        =$conteo["nbrDepartamento"];
            if($tipoReporte=="colegios_por_departamento"){
                $var_dato1        =$conteo["cantidadColegios"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalAlumnos"];
                $suma2            =$suma2+$var_dato2;

            }elseif($tipoReporte=="docentes_por_departamento"){
                $var_dato1        =$conteo["totalDocentesMasculinos"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalDocentesFemeninos"];
                $suma2            =$suma2+$var_dato2;
            }else{
                //$cantidad1='totalSinFines';
                //$cantidad2='totalConFines';
                $var_dato1        =$conteo["totalSinFines"];
                $suma1            =$suma1+$var_dato1;
                
                $var_dato2        =$conteo["totalConFines"];
                $suma2            =$suma2+$var_dato2;
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B'.$row, $nbrDepartamento)
                ->setCellValue('C'.$row, $var_dato1)
                ->setCellValue('D'.$row, $var_dato2);
                $row++;
        }
        
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('B'.$row, "Totales")
            ->setCellValue('C'.$row, $suma1)
            ->setCellValue('D'.$row, $suma2);
            
        
            
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            ''.$tipoReporte.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
            
            //Fin excel
        }
    }
}
