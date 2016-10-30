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

    private $autoridades;

    public function __construct(){
        $this->autoridades=array(
            'ministro' => array(
                'titulo' => '',
                'nombre' => 'Carlos Mauricio Canjura Linares',
                'cargo' => 'Ministro de Educación',
            ),
            'viceMinistro' => array(
                'titulo' => '',
                'nombre' => 'Francisco Humberto Castaneda Monterrosa',
                'cargo' => 'Viceministro de Educación',
            ),
            'directorGestion' => array(
                'titulo' => 'Lic.',
                'nombre' => 'Renzo Uriel Valencia Arana',
                'cargo' => 'Director Nacional de Gestión Educativa',
            ),
            'jefeAcreditacion' => array(
                'titulo' => 'Lic.',
                'nombre' => 'Juan Carlos Arteaga Mena',
                'cargo' => 'Jefe Departamento de Acreditación Institucional',
            ),
        );
    }

    public function getAutoridad($autoridad,$formato=null){
        if(!isset($this->autoridades[$autoridad])){
            return 'No definido';
        }
        switch ($formato) {
            case 'ST':
                return $this->autoridades[$autoridad]['nombre'] . "\n" . $this->autoridades[$autoridad]['cargo'];
                break;
            default:
                return $this->autoridades[$autoridad]['titulo'] . ' ' . $this->autoridades[$autoridad]['nombre'] . "\n" . $this->autoridades[$autoridad]['cargo'];
                break;
        }
    }
    
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
            /*->andWhere('exists (
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
            ->andWhere('exists (
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
            ->andWhere('exists (
                select 1
                    from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion e
                        where e.codEstadoAcreditacion in (\'AC\',\'AO\')
                            and a.idEstadoAcreditacion=e.idEstadoAcreditacion
                            and a.idCentroEducativo=ce.idCentroEducativo
            )')
        //->setParameter('nbr', $criterio)
        //->setParameter('fechaRef', new \DateTime())
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
        
        
        //Header
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                $phpExcelObject->getProperties()
                    ->setCreator("Sistema de Acreditación")
                    ->setLastModifiedBy("")
                    ->setTitle("Sistema de Acreditación")
                    ->setSubject("")
                    ->setDescription("")
                    ->setKeywords("");
                $phpExcelObject->setActiveSheetIndex(0);
                $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
                //Fin header
        
        
        
        
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
        $columns=array(
            array('title' => '','border' => 1,'width' => 25,),
            array('title' => '','border' => 1,),
        );
        $rows=array(
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
        );
        $differentHeader=array();
        $showHeaders=false;
        if($this->formato=='pdf'){
            $this->pdfObj->setFilasZebra(true);
            $this->pdfObj->dataTable($columns,$rows,$differentHeader,$showHeaders);
            $this->pdfObj->setFilasZebra(false);
            $this->pdfObj->newLine();
        }
        else{
            $this->phpExcelDataTable($columns,$rows,$differentHeader,$showHeaders);
            $this->excelFila++;
        }
    }

    private function encabezadoCuantitativo($em,$anio,$idCentroEducativo)
    {
        $titulo='RESULTADOS DE LA EVALUACIÓN EXTERNA PARA LA ACREDITACIÓN INSTITUCIONAL DE CENTROS EDUCATIVOS PRIVADOS AÑO ' . $anio;
        if($this->formato=='pdf'){
            $this->pdfObj->setHeaderType('newLogoHeader');
            $this->pdfObj->setFooterType('simpleFooter');
            $this->pdfObj->startPageGroup();
            $this->pdfObj->AddPage();
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),$titulo,0,'C');
            $this->pdfObj->newLine();
            $this->pdfObj->SetFontSize(9);
        }
        else{
            $this->phpExcelObject->getProperties()
                ->setCreator("Sistema de Acreditación")
                ->setLastModifiedBy("")
                ->setTitle("Sistema de Acreditación")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("");
            $this->phpExcelObject->setActiveSheetIndex(0);
            $this->phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');

            $this->phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue($this->excelColumna . $this->excelFila, $titulo);
            $this->excelFila+=2;
        }

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

    private function incExcelColumna(){
        if(strlen($this->excelColumna)==1){
            $prefCol='';
            $col=ord($this->excelColumna);
        }
        else{
            $prefCol=substr($this->excelColumna,1,1);
            $col=ord(substr($this->excelColumna,-1));
        }
        if(chr($col)=='Z'){
            $col=ord('A');
            if($prefCol==''){
                $prefCol='A';
            }
            else{
                $prefCol=chr(ord($prefCol)+1);
            }
        }
        else{
            $col++;
        }
        $this->excelColumna=$prefCol . chr($col);
    }

    private function phpExcelDataTable($columns,$rows,$differentHeader=array('type' => 'N','text' => ''),$showHeaders=true){
        $orgCol=$this->excelColumna;

        $sinTitulo=true;
        foreach ($columns as $column) {
            if($column['title']!=''){
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $column['title']);
                $sinTitulo=false;
            }
            if(isset($column['width'])){
                $this->phpExcelObject->getActiveSheet()
                    ->getColumnDimension($this->excelColumna)
                    ->setWidth($column['width']);
            }
                $this->incExcelColumna();
        }
        if(!$sinTitulo){
            $this->excelFila++;
        }

        foreach ($rows as $row) {
            $this->excelColumna=$orgCol;
            foreach ($row as $cell) {
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $cell);
                $this->incExcelColumna();
            }
            $this->excelFila++;
        }

        $this->excelColumna=$orgCol;
    }
    
    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function informeCuantitativoAction(Request $request)
    {
        $anio=$request->get('anno');
        $this->formato=$request->get('formato');
        $idCentroEducativo=$request->get('centrosEducativo');
        $versionParaCoordinador=$request->get('versionParaCoordinador');

        $em = $this->getDoctrine()->getManager();
        if($this->formato=='pdf'){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();
        }
        else{
            $this->phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $this->excelColumna='A';
            $this->excelFila=1;
        }

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
        $puntosPorCriterioTitulos=array(
            array('title' => 'CRITERIOS PROMEDIADOS BÁSICA, MEDIA Y PARVULARIA','border' => 1,'width' => 50,),
            array('title' => 'Puntuación global esperada','border' => 1,),
            array('title' => 'Puntuación global obtenida','border' => 1,),
            array('title' => 'Diferencia','border' => 1,),
        );
        if($this->formato=='pdf'){
            $this->pdfObj->setColorearTotales(true);
            $this->pdfObj->dataTable($puntosPorCriterioTitulos,$puntosPorCriterioData,array());
            $this->pdfObj->newLine();
        }
        else{
            $this->phpExcelDataTable($puntosPorCriterioTitulos,$puntosPorCriterioData,array());
            $this->excelFila++;
        }
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
                if($this->formato=='pdf'){
                    $this->pdfObj->AddPage();
                }
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
                    $puntosPorIndicadorDataTitulos=array(
                        array('title' => 'INDICADORES','border' => 1,'width' => 50,),
                        array('title' => 'Puntuación global esperada','border' => 1,),
                        array('title' => 'Puntuación global obtenida','border' => 1,),
                        array('title' => 'Diferencia','border' => 1,),
                    );
                    $nbrCriterioStr=$criterio['nbrSeccion'] . "\n" . $formulario['nbrFormulario'];
                    if($this->formato=='pdf'){
                        $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),$nbrCriterioStr,0,'C');
                        $this->pdfObj->newLine();

                        $this->pdfObj->dataTable($puntosPorIndicadorDataTitulos,$puntosPorIndicadorData,array());
                        $this->pdfObj->newLine();
                    }
                    else{
                        $this->phpExcelObject->getActiveSheet()
                            ->setCellValue($this->excelColumna . $this->excelFila, $nbrCriterioStr);
                        $this->excelFila+=2;

                        $this->phpExcelDataTable($puntosPorIndicadorDataTitulos,$puntosPorIndicadorData,array());
                        $this->excelFila++;
                    }
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
                    ->andWhere('fce.idCentroEducativo=:idCentroEducativo')
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

                $observacionesTitulos=array(
                    array('title' => 'Observaciones','border' => 1,),
                );
                if($this->formato=='pdf'){
                    $this->pdfObj->setColorearTotales(false);
                    $this->pdfObj->dataTable($observacionesTitulos,$observacionesArr,array());
                    $this->pdfObj->newLine();
                }
                else{
                    $this->phpExcelDataTable($observacionesTitulos,$observacionesArr,array());
                    $this->excelFila++;
                }
            }

        }

        if(!$versionParaCoordinador){
            $resultadoStr='RESULTADO DE ACREDITACIÓN INSTITUCIONAL';
            if($this->formato=='pdf'){
                $margins=$this->pdfObj->getMargins();
                $col1=60;
                $col2=20;
                $this->pdfObj->setX($margins['left']+($this->pdfObj->getWorkAreaWidth()-$col1-$col2)/2);
                $this->pdfObj->MultiCell($col1,2*$this->pdfObj->getLineHeight(),$resultadoStr,1,'C',
                    false,0,'','',true,0,false,true,2*$this->pdfObj->getLineHeight(),'M'
                    );
                $this->pdfObj->SetFontSize(14);
                $this->pdfObj->MultiCell($col2,2*$this->pdfObj->getLineHeight(),$resultado,1,'C',
                    false,1,'','',true,0,false,true,2*$this->pdfObj->getLineHeight(),'M'
                    );
                $this->pdfObj->newLine(2);

                $this->pdfObj->SetFontSize(9);
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),$this->getAutoridad('directorGestion'),0,'C',false,0);
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),$this->getAutoridad('jefeAcreditacion'),0,'C');
            }
            else{
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $resultadoStr);
                $this->incExcelColumna();
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $resultado);
                $this->incExcelColumna();
                $this->excelFila+=2;

                $this->excelColumna='A';
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $this->getAutoridad('directorGestion'));
                $this->incExcelColumna();
                $this->incExcelColumna();
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $this->getAutoridad('jefeAcreditacion'));
            }
        }

        $outputFileName="informeCuantitativo-" . $this->centroEducativo['codCentroEducativo'] . "-$anio";
        if($this->formato=='pdf'){
            $this->pdfObj->Output($outputFileName . '.pdf', 'I');
        }
        else{
            $writer = $this->get('phpexcel')->createWriter($this->phpExcelObject, 'Excel5');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $outputFileName . '.xls'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);
            return $response;
        }
    }

    /**
     * @Security("has_role('ROLE_MINED') or has_role('ROLE_EVALUADOR')")
     */
    public function informeCualitativoAction(Request $request)
    {
        $anio=$request->get('anno');
        $this->formato=$request->get('formato');
        $idCentroEducativo=$request->get('centrosEducativo');

        $em = $this->getDoctrine()->getManager();
        if($this->formato=='pdf'){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();
        }
        else{
            $this->phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $this->excelColumna='A';
            $this->excelFila=1;
        }

        $this->encabezadoCuantitativo($em,$anio,$idCentroEducativo);
        if($this->formato=='pdf'){
            $this->pdfObj->setFooterType('imageFooter');
        }

        $formularios=$em->createQueryBuilder()
            ->select('f.codFormulario, f.nbrFormulario, fce.idFormularioPorCentroEducativo')
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
                if($this->formato=='pdf'){
                    $this->pdfObj->AddPage();
                }
                $this->informacionGeneralCentro();
            }
            $primerFormulario=false;

            $observacionStr='OBSERVACIONES PARA ELABORAR EL PLAN DE MEJORAMIENTO INSTITUCIONAL' . "\n" . $formulario['nbrFormulario'];
            if($this->formato=='pdf'){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),$observacionStr,0,'C');
                $this->pdfObj->newLine();
            }
            else{
                $this->phpExcelObject->getActiveSheet()
                    ->setCellValue($this->excelColumna . $this->excelFila, $observacionStr);
                $this->excelFila+=2;
            }

            $criterios=$em->createQueryBuilder()
                ->select('s.nbrSeccion, sfce.observacion')
                ->from('AcreditacionBundle:Formulario', 'f')
                ->join('f.secciones','s')
                ->leftJoin('s.seccionesPorFormularioPorCentroEducativo','sfce','WITH','sfce.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
                ->andWhere('f.codFormulario=:codFormulario')
                ->andWhere('exists (
                    select 1
                    from AcreditacionBundle:Pregunta p
                    where p.ponderacionMaxima is not null
                    and p.idSeccion=s.idSeccion
                )')
                ->orderBy('s.codSeccion')
                    ->setParameter('codFormulario',$formulario['codFormulario'])
                    ->setParameter('idFormularioPorCentroEducativo',$formulario['idFormularioPorCentroEducativo'])
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

            $arrCriteriosTitulos=array(
                array('title' => 'CRITERIOS','border' => 1,'width' => 50,'align' => 'C'),
                array('title' => 'OBSERVACIONES','border' => 1,),
            );
            if($this->formato=='pdf'){
                $this->pdfObj->setFilasZebra(true);
                $this->pdfObj->dataTable($arrCriteriosTitulos,$arrCriterios,array());
                $this->pdfObj->setFilasZebra(true);
                $this->pdfObj->newLine();
            }
            else{
                $this->phpExcelDataTable($arrCriteriosTitulos,$arrCriterios,array());
                $this->excelFila++;
            }
        }

        $outputFileName="informeCualitativo-" . $this->centroEducativo['codCentroEducativo'] . "-$anio";
        if($this->formato=='pdf'){
            $this->pdfObj->Output($outputFileName . '.pdf', 'I');
        }
        else{
            $writer = $this->get('phpexcel')->createWriter($this->phpExcelObject, 'Excel5');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $outputFileName . '.xls'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);
            return $response;
        }
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
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->getAutoridad('ministro','ST'), 0, 'C');
        $this->pdfObj->SetXY($x+92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->getAutoridad('viceMinistro','ST'), 0, 'C');
        $this->pdfObj->SetXY($x+2*92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), $this->getAutoridad('directorGestion','ST'), 0, 'C');

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

        $this->pdfObj->MultiCell(80,$this->pdfObj->getLineHeight(),$this->getAutoridad('directorGestion'),'T','L');

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
        
        $formato=$request->get('formato');
        $tipo_formato="pdf";
        
        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        
        if($formato==$tipo_formato){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();

            $this->pdfObj->setHeaderType('newLogoHeader');
            $this->pdfObj->setFooterType('simpleFooter');
            $this->pdfObj->startPageGroup();
            $this->pdfObj->AddPage();
        }

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
            if($formato==$tipo_formato){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'ESTADÍSTICO GENERAL DE EVALUACIÓN',0,'C');
                $this->pdfObj->newLine();
            }

            $estados=$em->createQueryBuilder()
                ->select('e.codEstadoAcreditacion, e.nbrEstadoAcreditacion')
                ->from('AcreditacionBundle:EstadoAcreditacion','e')
                ->orderBy('e.idEstadoAcreditacion')
                    ->getQuery()->getResult();
            if($formato==$tipo_formato){
                $this->pdfObj->crossTab($porAnioEstado,'anio','Año',$estados,'codEstadoAcreditacion','nbrEstadoAcreditacion');

                $this->pdfObj->dataTable(array(
                    array('title' => 'No evaluados','border' => 1,'align' => 'R',),
                ),array(
                    array($noEvaluados),
                ));
            }else{
                /////////////////////////////////////////////////////////////////////////////
                //Inicia excel
                $excel_titulo="ESTADÍSTICO GENERAL DE EVALUACIÓN";
                
                //Header
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                $phpExcelObject->getProperties()
                    ->setCreator("Sistema de Acreditación")
                    ->setLastModifiedBy("")
                    ->setTitle("Sistema de Acreditación")
                    ->setSubject("")
                    ->setDescription("")
                    ->setKeywords("");
                $phpExcelObject->setActiveSheetIndex(0);
                $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
                //Fin header
                
                $phpExcelObject->setActiveSheetIndex(0)
                    ->mergeCells('B1:H1')
                    ->setCellValue('B1', $excel_titulo);
                $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue('E2','Totales');
                    
                $xlsFila=4; //3?
                
                $xlsDataArr=array();
                foreach ($porAnioEstado as $value) {
                    $xlsDataArr[$value['anio']][$value['codigo']]=$value['cantidad'];
                }
                $letra=67;
                $filaXls=2; //1?
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B' . $filaXls,'Año')
                    ->getColumnDimension('B')
                    ->setWidth(15);
                foreach ($estados as $estado) {
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue(chr($letra) . $filaXls,$estado['nbrEstadoAcreditacion'])
                        ->getColumnDimension(chr($letra))
                        ->setWidth(15);
                        $letra++;
                }
                //cuerpo
                $letra=66;
                $filaXls=3; //3?
                foreach($xlsDataArr as $anio => $registro){
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue(chr($letra) . $filaXls,$anio);
                    $letra++;
                    reset($estados);
                    foreach ($estados as $estado) {
                        $phpExcelObject->setActiveSheetIndex(0)
                            ->setCellValue(chr($letra) . $filaXls,(isset($registro[$estado['codEstadoAcreditacion']])?$registro[$estado['codEstadoAcreditacion']]:0));
                            $phpExcelObject->setActiveSheetIndex(0)
                                ->setCellValue('E'.$filaXls,'=sum(C'.$filaXls.':D'.$filaXls.')');
                        $letra++;
                    }
                    $filaXls++;
                }
                
                //Encabezados
                $xlsFila=($xlsFila+2);
                $phpExcelObject->setActiveSheetIndex(0)
                    ->mergeCells('B'.$xlsFila.':D'.$xlsFila)
                    ->setCellValue('B'.$xlsFila, 'No evaluados');
                    
                $xlsFila=($xlsFila+1);    
               
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue('B' . $xlsFila,$noEvaluados);
                        
                $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
                $response = $this->get('phpexcel')->createStreamedResponse($writer);
                $dispositionHeader = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    'reporte.xls'
                );
                $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
                $response->headers->set('Pragma', 'public');
                $response->headers->set('Cache-Control', 'maxage=1');
                $response->headers->set('Content-Disposition', $dispositionHeader);
                return $response;
                //Fin excel
                //////////////////////////////////////////////////////////////////////////
            }
            
            
            
            
            
            
            
            
            
            
        }
        else{
            if($formato==$tipo_formato){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'LISTADO GENERAL DE EVALUACIÓN POR ESTADO',0,'C');
                $this->pdfObj->newLine();

                $columnArr=array(
                    array('title' => 'Código','border' => 1,'width' => 12,),
                    array('title' => 'Nombre','border' => 1,),
                    array('title' => 'Dirección','border' => 1,),
                );
            }
            
            
            
            
            
            $reporteArr=array();
            $codEstadoAcreditacionAnt='';
            foreach ($porAnioEstado as $value) {

                if($value['codEstadoAcreditacion']!=$codEstadoAcreditacionAnt){
                    if(count($reporteArr)>0){
                        if($formato==$tipo_formato){
                            $this->pdfObj->dataTable($columnArr,$reporteArr);
                            $this->pdfObj->newLine();
                        }
                            $reporteArr=array();
                    }
                    if($formato==$tipo_formato){
                        $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'Año ' . $value['anio'] . ' - ' . $value['nbrEstadoAcreditacion'],0,'L');
                        $this->pdfObj->newLine();
                    }
                }

                $reporteArr[]=array(
                    $value['codCentroEducativo'],
                    $value['nbrCentroEducativo'],
                    $value['direccionCentroEducativo'],
                );

                $codEstadoAcreditacionAnt=$value['codEstadoAcreditacion'];
            }
            if(count($reporteArr)>0){
                if($formato==$tipo_formato){
                    $this->pdfObj->dataTable($columnArr,$reporteArr);
                    $this->pdfObj->newLine();
                }
            }

            $reporteArr=array();
            foreach ($noEvaluados as $value) {
                $reporteArr[]=array(
                    $value['codCentroEducativo'],
                    $value['nbrCentroEducativo'],
                    $value['direccionCentroEducativo'],
                );
            }
            if($formato==$tipo_formato){
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'No evaluados',0,'L');
                $this->pdfObj->newLine();
                $this->pdfObj->dataTable($columnArr,$reporteArr);
            }
        }
        if($formato==$tipo_formato){
            $this->pdfObj->Output((!$listado?'estadisticoGeneral':'listadoGeneral') . ".pdf", 'I');
        }else{
            
            
            
            /////////////////////////////////////////////////////////////////////////////
            //Inicia excel
            $excel_titulo="ESTADÍSTICO GENERAL DE EVALUACIÓN";
            
            //Header
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()
                ->setCreator("Sistema de Acreditación")
                ->setLastModifiedBy("")
                ->setTitle("Sistema de Acreditación")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("");
            $phpExcelObject->setActiveSheetIndex(0);
            $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
            //Fin header
            
            $phpExcelObject->setActiveSheetIndex(0)
                ->mergeCells('B1:H1')
                ->setCellValue('B1', $excel_titulo);

            $phpExcelObject->setActiveSheetIndex(0)
                ->mergeCells('B2:H2')
                ->setCellValue('B2', 'Centros educativos evaluados');
                
            //Encabezados
            $styleArray = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => 'FF0000'),
                    'size'  => 15,
                    'name'  => 'Verdana'
                )
            );

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B3','Código')
                ->getColumnDimension('B')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('C3','Nombre')
                ->getColumnDimension('C')
                ->setWidth(60);
            $phpExcelObject->setActiveSheetIndex(0)    
                ->setCellValue('D3','Dirección')
                ->getColumnDimension('D')
                ->setWidth(100);
                    
                    
            $xlsFila=4; //3?
            foreach($porAnioEstado as $registro){
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B' . $xlsFila,$registro['codCentroEducativo'])
                    ->setCellValue('C' . $xlsFila,$registro['nbrCentroEducativo'])
                    ->setCellValue('D' . $xlsFila,$registro['direccionCentroEducativo']);
                $xlsFila++;
            }
            //Encabezados
            $xlsFila=($xlsFila+2);
            
            $phpExcelObject->setActiveSheetIndex(0)
                ->mergeCells('B'.$xlsFila.':H'.$xlsFila)
                ->setCellValue('B'.$xlsFila, 'Centros educativos no evaluados');

            $xlsFila=($xlsFila+1);
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B'.$xlsFila,'Código')
                ->setCellValue('C'.$xlsFila,'Nombre')
                ->setCellValue('D'.$xlsFila,'Dirección');
                
                
            $xlsFila=($xlsFila+1);    
            foreach($noEvaluados as $registro){
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B' . $xlsFila,$registro['codCentroEducativo'])
                    ->setCellValue('C' . $xlsFila,$registro['nbrCentroEducativo'])
                    ->setCellValue('D' . $xlsFila,$registro['direccionCentroEducativo']);
                $xlsFila++;
            }
          
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'reporte.xls'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);
            return $response;
            //Fin excel
            //////////////////////////////////////////////////////////////////////////
        }
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
                
                //Header
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                $phpExcelObject->getProperties()
                    ->setCreator("Sistema de Acreditación")
                    ->setLastModifiedBy("")
                    ->setTitle("Sistema de Acreditación")
                    ->setSubject("")
                    ->setDescription("")
                    ->setKeywords("");
                $phpExcelObject->setActiveSheetIndex(0);
                $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
                //Fin header
                
                
                $contar="67";
                $phpExcelObject->setActiveSheetIndex(0)
                    ->mergeCells('B1:H1')
                    ->setCellValue('B1', $excel_titulo)
                    ->setCellValue('B3', "Año")
                    ->setCellValue('H3', "Totales");
                foreach($criterios as $criterio){
                    $letra=chr($contar);
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue(''.$letra.'3', $criterio['nbrSeccion'])
                        ->getColumnDimension($letra)
                        ->setWidth(15);
                        $contar++;
                }
                $row = 4;
                $contarr="67";
                $arrFilas=array();
                    foreach($promedios as $promedio){
                        if(!isset($arrFilas[$promedio['anio']])){
                            $arrFilas[$promedio['anio']]=array();
                        }
                        $arrFilas[$promedio['anio']][$promedio['codigo']]=$promedio['cantidad'];
                    }
                reset($criterios);
                $row="4";
                foreach($arrFilas as $anio => $fila){
                    //echo $anio; //esto debería mandarse a xls
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$row, $anio);
                reset($criterios);
                $contar="67";
                    $sumaCriterio="0";
                    foreach ($criterios as $criterio) {
                        $letra=chr($contar);
                        if(isset($fila[$criterio['idSeccion']])){
                        
                                ////echo $fila[$criterio['idSeccion']]; //esto debería mandarse a xls
                                $phpExcelObject->setActiveSheetIndex(0)
                                ->setCellValue($letra.$row, $fila[$criterio['idSeccion']]);
                                $sumaCriterio=$sumaCriterio+$fila[$criterio['idSeccion']];
                                
                                $phpExcelObject->setActiveSheetIndex(0)
                                ->setCellValue('H'.$row, $sumaCriterio);
                            }
                            $contar++;
                        }
                        
                        $row++;
                    }
                    $row=($row-1);
                    $rowv=($row+1);
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$rowv, "Totales")
                    ->setCellValue('C'.$rowv, '=sum(C2:C'.$row.')')
                    ->setCellValue('D'.$rowv, '=sum(D2:D'.$row.')')
                    ->setCellValue('E'.$rowv, '=sum(E2:E'.$row.')')
                    ->setCellValue('F'.$rowv, '=sum(F2:F'.$row.')')
                    ->setCellValue('G'.$rowv, '=sum(G2:G'.$row.')')
                    ->setCellValue('H'.$rowv, '=sum(H2:H'.$row.')');
                
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
            if($formato==$formato_tipo){
                $this->pdfObj->SetFontSize(12);
                $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),'PROMEDIOS POR INDICADOR',0,'C');
                $this->pdfObj->newLine();
                $this->pdfObj->SetFontSize(6);
                $this->pdfObj->crossTab($promedios,'anio','Año',$indicadores,'idIndicador','nbrIndicador',2);
                $this->pdfObj->Output("promedio" . str_replace(' ','',ucwords(str_replace('_',' ',$tipoReporte))) . ".pdf", 'I');
            }else{
            
            //Inicia excel
                $excel_titulo="PROMEDIOS POR INDICADOR";
                
                
                //Header
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                $phpExcelObject->getProperties()
                    ->setCreator("Sistema de Acreditación")
                    ->setLastModifiedBy("")
                    ->setTitle("Sistema de Acreditación")
                    ->setSubject("")
                    ->setDescription("")
                    ->setKeywords("");
                $phpExcelObject->setActiveSheetIndex(0);
                $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
                //Fin header
                
                $contar="67";
                $A="A";
                $rango="67";
                $phpExcelObject->setActiveSheetIndex(0)
                    ->mergeCells('B1:H1')
                    ->setCellValue('B1', $excel_titulo)
                    ->setCellValue('B3', "Año")
                     ->setCellValue('AJ3', "Totales");
                foreach($indicadores as $indicador){
                    if($rango<=90){
                        $letra=chr($contar);
                    }elseif($rango==91){
                        $contar="65";
                        $letra=$A.chr($contar);
                    }else{
                        $letra=$A.chr($contar);
                    }
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue(''.$letra.'3', $indicador['nbrIndicador'])
                        ->getColumnDimension($letra)
                        ->setWidth(15);
                        $contar++;
                        $rango++;
                    }
                $row = 4;
                $contarr="67";
                $arrFilas=array();
                    foreach($promedios as $promedio){
                        if(!isset($arrFilas[$promedio['anio']])){
                            $arrFilas[$promedio['anio']]=array();
                        }
                        $arrFilas[$promedio['anio']][$promedio['codigo']]=$promedio['cantidad'];
                    }
                reset($indicadores);
                $row="4";
                foreach($arrFilas as $anio => $fila){
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$row, $anio);
                reset($indicadores);
                $contar="67";
                $rango="67";
                $A="A";
                    $sumaIdicador="0";
                    foreach ($indicadores as $indicador) {
                        $letra=chr($contar);
                        if($rango<=90){
                        $letra=chr($contar);
                    }elseif($rango==91){
                        $contar="65";
                        $letra=$A.chr($contar);
                    }else{
                        $letra=$A.chr($contar);
                    }
                        if(isset($fila[$indicador['idIndicador']])){
                                $phpExcelObject->setActiveSheetIndex(0)
                                ->setCellValue($letra.$row, $fila[$indicador['idIndicador']]);
                                $sumaIdicador=$sumaIdicador+$fila[$indicador['idIndicador']];
                                $phpExcelObject->setActiveSheetIndex(0)
                                ->setCellValue('AJ'.$row, $sumaIdicador);
                            }
                            $contar++;
                            $rango++;
                        }
                        
                        $row++;
                    }
                    $row=($row-1);
                    $rowv=($row+1);
                    $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$rowv, "Totales");
                     $A="A";
                     $contar=65;
                    $rango=$rango-1;
                    for($i=67; $i<=$rango; $i++) {
                        if($i<=90){
                            $letra=chr($i);
                        }else{
                            $letra=$A.chr($contar);
                            $contar++;
                        }
                        $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue($letra.$rowv, '=sum('.$letra.'2:'.$letra.$row.')');
                    }
                    $phpExcelObject->setActiveSheetIndex(0)
                        ->setCellValue('AJ'.$rowv, '=sum(AJ2:AJ'.$row.')');
                
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
            
            //Header
                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
                $phpExcelObject->getProperties()
                    ->setCreator("Sistema de Acreditación")
                    ->setLastModifiedBy("")
                    ->setTitle("Sistema de Acreditación")
                    ->setSubject("")
                    ->setDescription("")
                    ->setKeywords("");
                $phpExcelObject->setActiveSheetIndex(0);
                $phpExcelObject->getActiveSheet()->setTitle('Sistema de Acreditación');
                //Fin header
            
            
            
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
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function actividadUsuarioFormAction(){
        $em = $this->getDoctrine()->getManager();

        $usuarios=$em->getRepository('AcreditacionBundle:Usuario')->findAll();
        $tiposAccion=$em->getRepository('AcreditacionBundle:TipoAccionUsuario')->findAll();

        return $this->render('reportes/actividadUsuarioForm.html.twig',array(
            'usuarios' => $usuarios,
            'tiposAccion' => $tiposAccion,
        ));
    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function actividadUsuarioAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $fechaIni=$request->get('fechaIni');
        $fechaFin=$request->get('fechaFin');
        $idUsuario=$request->get('idUsuario');
        $idTipoAccionUsuario=$request->get('idTipoAccionUsuario');
        $detalle=$request->get('detalle');
        $formato=$request->get('formato');

        $queryAcciones=$em->createQueryBuilder()
            ->select('a.fechaHora, CONCAT(u.nombres,\' \',u.apellidos) as nbrUsuario, a.direccionIp, t.descripcionTipoAccionUsuario, a.detalleAccionUsuario')
            ->from('AcreditacionBundle:AccionPorUsuario','a')
            ->join('a.idUsuario','u')
            ->join('a.idTipoAccionUsuario','t')
            ->where('1=1');

        if($fechaIni){
            $queryAcciones
                ->andWhere('a.fechaHora>=:fechaIni')
                    ->setParameter('fechaIni',new \DateTime($fechaIni));
        }
        if($fechaFin){
            $fechaFinRef=new \DateTime($fechaFin);
            $fechaFinRef->add(new \DateInterval('P1D'));
            $queryAcciones
                ->andWhere('a.fechaHora<:fechaFin')
                    ->setParameter('fechaFin',$fechaFinRef);
        }
        if($idUsuario){
            $queryAcciones
                ->andWhere('a.idUsuario=:idUsuario')
                    ->setParameter('idUsuario',$idUsuario);
        }
        if($idTipoAccionUsuario){
            $queryAcciones
                ->andWhere('a.idTipoAccionUsuario=:idTipoAccionUsuario')
                    ->setParameter('idTipoAccionUsuario',$idTipoAccionUsuario);
        }
        if($detalle){
            $queryAcciones
                ->andWhere('a.detalleAccionUsuario like :detalleAccionUsuario')
                    ->setParameter('detalleAccionUsuario','%' . str_replace(' ','%',$detalle) . '%');
        }

        $acciones=$queryAcciones
            ->getQuery()->getResult();

        $nbrReporte='Reporte de actividad de usuarios';
        $nbrReporteCorto='Actividad de usuarios';

        if($formato=='pdf'){
            $this->pdfObj=$this->get("white_october.tcpdf")->create();

            $this->pdfObj->setHeaderType('newLogoHeader');
            $this->pdfObj->setFooterType('simpleFooter');
            $this->pdfObj->startPageGroup();
            $this->pdfObj->AddPage('L');
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth(),$this->pdfObj->getLineHeight(),$nbrReporte,0,'C');
            $this->pdfObj->newLine();
            $this->pdfObj->SetFontSize(9);

            $accionesArr=array();
            foreach ($acciones as $accion) {
                $accionesArr[]=array(
                    $accion['fechaHora']->format('m/d/Y H:i:s'),
                    $accion['nbrUsuario'],
                    $accion['direccionIp'],
                    $accion['descripcionTipoAccionUsuario'],
                    $accion['detalleAccionUsuario'],
                );
            }
            $this->pdfObj->dataTable(array(
                    array('title' => 'Fecha/hora','border' => 1,'width' => 12,),
                    array('title' => 'Usuario','border' => 1,'width' => 12,),
                    array('title' => 'Dirección IP','border' => 1,'width' => 12,),
                    array('title' => 'Acción','border' => 1,'width' => 12,),
                    array('title' => 'Detalle','border' => 1,),
                ),$accionesArr,array());

            $this->pdfObj->Output("reporteActividadUsuarios.pdf", 'I');
        }
        else{
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()
                ->setCreator("")
                ->setLastModifiedBy("")
                ->setSubject($nbrReporte)
                ->setDescription($nbrReporte)
                ->setKeywords($nbrReporte);
            $phpExcelObject->getActiveSheet()->setTitle($nbrReporteCorto);
            
            
            
            $phpExcelObject->setActiveSheetIndex(0)
                ->mergeCells('B1:F1')
                ->setCellValue('B1',$nbrReporte)
                ->setCellValue('B3','Fecha/hora')
                ->setCellValue('C3','Usuario')
                ->setCellValue('D3','Dirección IP')
                ->setCellValue('E3','Acción')
                ->setCellValue('F3','Detalle');

            $row=4;
            foreach ($acciones as $accion) {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B'.$row,$accion['fechaHora']->format('m/d/Y H:i:s'))
                    ->setCellValue('C'.$row,$accion['nbrUsuario'])
                    ->setCellValue('D'.$row,$accion['direccionIp'])
                    ->setCellValue('E'.$row,$accion['descripcionTipoAccionUsuario'])
                    ->setCellValue('F'.$row,$accion['detalleAccionUsuario']);
                $row++;
            }

            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('B')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('C')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('D')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('E')
                ->setWidth(15);
            $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('F')
                ->setWidth(50);

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $nbrReporte . '.xls'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);
            return $response;
        }
    }
}
