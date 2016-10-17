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
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31')
                    ->getQuery()->getSingleResult();

        $this->informacionGeneralCentro();
    }
    
    public function informeCuantitativoAction(Request $request)
    {
        $anio=$request->get('anio');
        $idCentroEducativo=$request->get('idCentroEducativo');
        $versionParaCoordinador=$request->get('versionParaCoordinador');
///falta enviarle parámetros por POST
///falta enviarle parámetros por POST
///falta enviarle parámetros por POST
$anio=2016;
$anio=2016;
$anio=2016;
$idCentroEducativo=2118;
$idCentroEducativo=2118;
$idCentroEducativo=2118;
$versionParaCoordinador=1;
$versionParaCoordinador=1;
$versionParaCoordinador=1;

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
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31')
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
                    ->setParameter('fechaIni',$anio . '-1-1')
                    ->setParameter('fechaFin',$anio . '-12-31')
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
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31')
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
                        ->setParameter('fechaIni',$anio . '-1-1')
                        ->setParameter('fechaFin',$anio . '-12-31')
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
                            ->setParameter('fechaIni',$anio . '-1-1')
                            ->setParameter('fechaFin',$anio . '-12-31')
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
                        ->setParameter('fechaIni',$anio . '-1-1')
                        ->setParameter('fechaFin',$anio . '-12-31')
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
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),'Lic. Renzo Uriel Valencia Arana
Director Nacional de Gestión Educativa',0,'C',false,0);
            $this->pdfObj->MultiCell($this->pdfObj->getWorkAreaWidth()/2,$this->pdfObj->getLineHeight(),'Lic. Juan Carlos Arteaga Mena
Jefe Departamento de Acreditación Institucional',0,'C');
        }

        $this->pdfObj->Output("informeCuantitativo-" . $this->centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');
    }

    public function informeCualitativoAction(Request $request)
    {
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
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31')
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
                    ->setParameter('fechaIni',$anio . '-1-1')
                    ->setParameter('fechaFin',$anio . '-12-31')
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

    public function diplomaAction(Request $request)
    {
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
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $centroEducativo=$em->createQueryBuilder()
            ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, a.fechaRegistro, a.fechaInicio, a.fechaFin, e.nbrEstadoAcreditacion')
            ->from('AcreditacionBundle:CentroEducativo', 'ce')
            ->join('ce.acreditaciones','a')
            ->join('a.idEstadoAcreditacion','e')
            ->where('ce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('a.fechaInicio between :fechaIni and :fechaFin')
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31')
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
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), 'Carlos Mauricio Canjura Linares
Ministro de Educación', 0, 'C');
        $this->pdfObj->SetXY($x+92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), 'Francisco Humberto Castaneda Monterrosa
Viceministro de Educación', 0, 'C');
        $this->pdfObj->SetXY($x+2*92,$y);
        $this->pdfObj->MultiCell(90, $this->pdfObj->getLineHeight(), 'Renzo Uriel Valencia Arana
Director Nacional de Gestión Educativa', 0, 'C');

        $this->pdfObj->Output("diploma-" . $centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');

    }

    public function estadisticoGeneralAction(Request $request)
    {
        $anio=$request->get('anio');
        $idMunicipio=$request->get('idMunicipio');
        $listado=$request->get('listado');
///falta enviarle parámetros por POST
///falta enviarle parámetros por POST
///falta enviarle parámetros por POST
//$anio=2016;
//$anio=2016;
//$anio=2016;
//$idMunicipio=5;
//$idMunicipio=5;
//$idMunicipio=5;
$listado=1;
$listado=1;
$listado=1;

        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $this->pdfObj=$this->get("white_october.tcpdf")->create();

        $this->pdfObj->setHeaderType('newLogoHeader');
        $this->pdfObj->setFooterType('simpleFooter');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage();

        $conteosPorAnioEstadoQuery=$em->createQueryBuilder()
            ->from('AcreditacionBundle:Acreditacion','a')
            ->join('a.idEstadoAcreditacion','e')
            ->join('a.idCentroEducativo','ce')
            ->where('1=1');
        $noEvaluadosQuery=$em->createQueryBuilder()
            ->from('AcreditacionBundle:CentroEducativo','ce')
            ->where('1=1');

///faltan todos los filtros
///faltan todos los filtros
///faltan todos los filtros
        $filtrosNoEvaluados='';
        if($anio){
            $conteosPorAnioEstadoQuery
                ->andWhere('a.fechaInicio between :fechaIni and :fechaFin')
                    ->setParameter('fechaIni',$anio . '-1-1')
                    ->setParameter('fechaFin',$anio . '-12-31');
            $filtrosNoEvaluados.='and a.fechaInicio between :fechaIni and :fechaFin';
            $noEvaluadosQuery
                ->setParameter('fechaIni',$anio . '-1-1')
                ->setParameter('fechaFin',$anio . '-12-31');
        }
        if($idMunicipio){
            $conteosPorAnioEstadoQuery
                ->andWhere('ce.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
            $noEvaluadosQuery
                ->andWhere('ce.idMunicipio=:idMunicipio')
                    ->setParameter('idMunicipio',$idMunicipio);
        }

        if(!$listado){
            $conteosPorAnioEstadoQuery
                ->select('YEAR(a.fechaRegistro) as anio, e.codEstadoAcreditacion, count(1) as cantidad')
                ->groupBy('anio, e.codEstadoAcreditacion')
                ->orderBy('anio');
            $noEvaluadosQuery
                ->select('count(1) as cantidad');
        }
        else{
            $colListado='ce.codCentroEducativo, ce.nbrCentroEducativo, ce.direccionCentroEducativo';
            $conteosPorAnioEstadoQuery
                ->select('YEAR(a.fechaRegistro) as anio, e.codEstadoAcreditacion, e.nbrEstadoAcreditacion, ' . $colListado)
                ->orderBy('anio, e.idEstadoAcreditacion, ce.codCentroEducativo');
            $noEvaluadosQuery
                ->select($colListado)
                ->orderBy('ce.codCentroEducativo');
        }

        $porAnioEstado=$conteosPorAnioEstadoQuery
                ->getQuery()->getResult();
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
                ->getQuery()->getResult();
        }

        if(!$listado){
            $conteosPorAnioEstadoArr=array();
            foreach ($porAnioEstado as $conteo) {
                if(!isset($conteosPorAnioEstadoArr[$conteo['anio']])){
                    $conteosPorAnioEstadoArr[$conteo['anio']]=array();
                }
                $conteosPorAnioEstadoArr[$conteo['anio']][$conteo['codEstadoAcreditacion']]=$conteo['cantidad'];
            }
            $estados=$em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findAll();
            $columnArr=array();
            $columnArr[]=array('title' => 'Año','border' => 1,);
            foreach ($estados as $estado) {
                $columnArr[]=array('title' => $estado->getNbrEstadoAcreditacion(), 'border' => 1, 'align' => 'R',);
            }

            $reporteArr=$totales=array();
            $totales[]='Totales';
            foreach ($conteosPorAnioEstadoArr as $anio => $value) {
                $tmp=array($anio);
                reset($estados);
                foreach ($estados as $estado) {
                    if(!isset($totales[$estado->getCodEstadoAcreditacion()])){
                        $totales[$estado->getCodEstadoAcreditacion()]=0;
                    }
                    if(!isset($value[$estado->getCodEstadoAcreditacion()])){
                        $tmp[]=0;
                    }
                    else{
                        $tmp[]=$value[$estado->getCodEstadoAcreditacion()];
                        $totales[$estado->getCodEstadoAcreditacion()]+=$value[$estado->getCodEstadoAcreditacion()];
                    }
                }
                $reporteArr[]=$tmp;
            }
            $reporteArr[]=array_values($totales);
            $this->pdfObj->dataTable($columnArr,$reporteArr);
            $this->pdfObj->newLine();

            $this->pdfObj->dataTable(array(
                array('title' => 'No evaluados','border' => 1,'align' => 'R',),
            ),array(
                array($noEvaluados),
            ));
        }
        else{

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
}
