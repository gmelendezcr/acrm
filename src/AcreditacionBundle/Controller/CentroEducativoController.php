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
use AcreditacionBundle\Entity\Acreditacion;
use AcreditacionBundle\Entity\AccionPorUsuario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AcreditacionBundle\Form\CentroEducativoType;
use AcreditacionBundle\Form\CuotaAnualPorGradoEscolarPorCentroEducativoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use PHPExcel_IOFactory;



class CentroEducativoController extends Controller{

    private $margenNota=0.5;
    private $uploadDir='/var/acrm/';

    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function listaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $res=$em->createQueryBuilder()
        ->select('ce.idCentroEducativo as ID_CENTRO_EDUCATIVO, ce.codCentroEducativo as COD_CENTRO_EDUCATIVO, ce.nbrCentroEducativo as NBR_CENTRO_EDUCATIVO,
        ce.direccionCentroEducativo as DIRECCION_CENTRO_EDUCATIVO, ce.totalAlumnos as TOTAL_ALUMNOS, ce.activo as ACTIVO,
        zg.nbrZonaGeografica as NBR_ZONA_GEOGRAFICA,
        depto.nbrDepartamento as NBR_DEPARTAMENTO, 
        mpio.nbrMunicipio as NBR_MUNICIPIO,
        jda.nbrJornadaCentroEducativo as NBR_JORNADA,
        tce.nbrTamannoCentroEducativo as NBR_TAMANNO')
            ->from('AcreditacionBundle:CentroEducativo', 'ce')
            ->join('ce.idMunicipio','mpio')
            ->join('mpio.idDepartamento','depto')
            ->join('depto.idZonaGeografica','zg')
            ->join('ce.idJornadaCentroEducativo','jda')
            ->join('ce.idTamannoCentroEducativo','tce')
            ->orderBy('ce.nbrCentroEducativo', 'ASC')
                ->getQuery()->getResult();
        return $this->render('centro-educativo/lista.index.html.twig',array(
            'lista'=>$res,
            'debug'=>true
        ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function listanbrAction(Request $request){
        $nbrq=$request->get('nbr');
        $nbr="%".$nbrq."%";
        $em = $this->getDoctrine()->getManager();
        //Consulta diferentes criterios
        $res=$em->createQueryBuilder()
        ->select('ce.idCentroEducativo as ID_CENTRO_EDUCATIVO, ce.codCentroEducativo as COD_CENTRO_EDUCATIVO, ce.nbrCentroEducativo as NBR_CENTRO_EDUCATIVO,
        ce.direccionCentroEducativo as DIRECCION_CENTRO_EDUCATIVO, ce.totalAlumnos as TOTAL_ALUMNOS, ce.activo as ACTIVO,
        zg.nbrZonaGeografica as NBR_ZONA_GEOGRAFICA,
        depto.nbrDepartamento as NBR_DEPARTAMENTO, 
        mpio.nbrMunicipio as NBR_MUNICIPIO,
        jda.nbrJornadaCentroEducativo as NBR_JORNADA,
        tce.nbrTamannoCentroEducativo as NBR_TAMANNO')
            ->from('AcreditacionBundle:CentroEducativo', 'ce')
            ->join('ce.idMunicipio','mpio')
            ->join('mpio.idDepartamento','depto')
            ->join('depto.idZonaGeografica','zg')
            ->join('ce.idJornadaCentroEducativo','jda')
            ->join('ce.idTamannoCentroEducativo','tce')
                ->where('ce.nbrCentroEducativo like :nbr')
                ->orWhere('ce.codCentroEducativo like :nbr')
                ->orWhere('ce.direccionCentroEducativo like :nbr')
                ->orWhere('ce.totalAlumnos like :nbr')
                ->orWhere('zg.nbrZonaGeografica like :nbr')
                ->orWhere('depto.nbrDepartamento like :nbr')
                ->orWhere('mpio.nbrMunicipio like :nbr')
                ->orWhere('jda.nbrJornadaCentroEducativo like :nbr')
                ->orWhere('tce.nbrTamannoCentroEducativo like :nbr')
                ->setParameter('nbr', $nbr)
                ->orderBy('ce.nbrCentroEducativo', 'ASC')
            ->getQuery()->getResult();
        return $this->render('centro-educativo/lista.index.html.twig',array(
            'lista'=>$res,
            'nbr'=>$nbrq
            ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $departamentos = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $zona = $em->getRepository('AcreditacionBundle:ZonaGeografica')->findAll();
        $jornadas = $em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->findAll();
        $tamannos = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        $zonasCE = $em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->findAll();
        $modalidades = $em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->findAll();
        return $this->render('centro-educativo/addcedu.index.html.twig', array(
            'departamentos' => $departamentos,
            'zonas' =>$zona,
            'jornadas'=> $jornadas,
            'tamannos'=> $tamannos,
            'zonasCE' => $zonasCE,
            'modalidades' => $modalidades,
        ));
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function addguardarAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $codigo=$request->get('codigo');
        $nombre=$request->get('nombre');
        $direccion=$request->get('direccion');
        $total_alumnos=$request->get('total_alumnos');
        $municipio=$request->get('municipio');
        $jornada=$request->get('jornada');
        $tamanno=$request->get('tamanno');
        $totalDocentesMasculinos=$request->get('totalDocentesMasculinos');
        $totalDocentesFemeninos=$request->get('totalDocentesFemeninos');

        $municipio = $em->getRepository('AcreditacionBundle:Municipio')->find($municipio);
        $jornada = $em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->find($jornada);
        $tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->find($tamanno);
        $zonaCE = $em->getRepository('AcreditacionBundle:ZonaCentroEducativo')->find($request->get('zonasCE'));
        $modalidad = $em->getRepository('AcreditacionBundle:ModalidadCentroEducativo')->find($request->get('modalidades'));

        $ce=new CentroEducativo();
        $ce->setCodCentroEducativo($codigo);
        $ce->setNbrCentroEducativo($nombre);
        $ce->setDireccionCentroEducativo($direccion);
        $ce->setTotalAlumnos($total_alumnos);
        $ce->setIdMunicipio($municipio);
        $ce->setIdJornadaCentroEducativo($jornada);
        $ce->setIdTamannoCentroEducativo($tamanno);
        $ce->setTotalDocentesMasculinos($totalDocentesMasculinos);
        $ce->setTotalDocentesFemeninos($totalDocentesFemeninos);
        $ce->setIdZonaCentroEducativo($zonaCE);
        $ce->setIdModalidadCentroEducativo($modalidad);
        
        $ce->setActivo('S');
        $em=$this->getDoctrine()->getManager();
        new AccionPorUsuario($em,$this->getUser(),'AC',$ce);
        $em->persist($ce);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_buscar',array('nbr'=>$codigo));
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function actualizarAction(Request $request){
        if ($request->getMethod() == "POST") {
            $EditForm->submit($request);
            if ($form->isValid()) {
                $postData = current($request->request->all());
            }
       }
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function borrarAction($id){
        $em = $this->getDoctrine()->getManager();
        $check_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        new AccionPorUsuario($em,$this->getUser(),'EC',$check_ced);
        $em->remove($check_ced);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_lista');
    }
    
   
    
    /**
     * Editar datos de centros
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function editarrAction(Request $request,  $id){
        if (!$id) {
            throw $this->createNotFoundException('No se encuentra el Centro Educativo  con ID = '.$id);
        }  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to and Userentity.'); 
        }
        $editForm = $this->createForm(new CentroEducativoType($em), $entity); 
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
            $codCentroEducativo = $editForm->get('codCentroEducativo')->getData();
            $nbrCentroEducativo = $editForm->get('nbrCentroEducativo')->getData();
            $direccionCentroEducativo = $editForm->get('direccionCentroEducativo')->getData();
            $TotalAlumnosCentroEducativo = $editForm->get('totalAlumnos')->getData();
            $idMunicipioCentroEducativo = $editForm->get('idMunicipio')->getData();
            $idJornadaCentroEducativo = $editForm->get('idJornadaCentroEducativo')->getData();
            $idTamannoCentroEducativo = $editForm->get('idTamannoCentroEducativo')->getData();
            
            $entity->setCodCentroEducativo($codCentroEducativo);
            $entity->setNbrCentroEducativo($nbrCentroEducativo);
            $entity->setDireccionCentroEducativo($direccionCentroEducativo);
            $entity->setTotalAlumnos($TotalAlumnosCentroEducativo);
            $entity->setIdMunicipio($idMunicipioCentroEducativo);
            $entity->setIdJornadaCentroEducativo($idJornadaCentroEducativo);
            $entity->setIdTamannoCentroEducativo($idTamannoCentroEducativo);
            
            $em = $this->getDoctrine()->getManager();
            new AccionPorUsuario($em,$this->getUser(),'MC',$entity);
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('centro_educativo_lista');
        }
        
        return $this->render('centro-educativo/editar.index.html.twig', 
            array(
                'id'=>$id,
                'form' => $editForm->createView(),
            ));
    }
    /*Fin*/
    
    /**
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function form_dig_corrAction(){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        if($idFormularioPorCentroEducativo){
            return $this->redirectToRoute('seccion_index');
        }
        else{
            $forms=$em->createQueryBuilder()
                ->select('f.idFormularioPorCentroEducativo')
                ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'f')
                ->join('f.idEstadoFormulario','e')
                ->where('f.idUsuarioDigita=:idUsuarioDigita')
                ->andWhere('e.codEstadoFormulario in (:codEstadoFormulario)')
                ->orderBy('e.codEstadoFormulario','desc')
                    ->setParameter('idUsuarioDigita',$this->getUser())
                    ->setParameter('codEstadoFormulario',array('NU','DI','RE','CO'))
                        ->getQuery()->getArrayResult();
            if(is_array($forms) && isset($forms[0])){
                $idFormularioPorCentroEducativo=$forms[0]['idFormularioPorCentroEducativo'];
                $session->set('idFormularioPorCentroEducativo', $idFormularioPorCentroEducativo);
                return $this->redirectToRoute('seccion_index');
            }
        }

        $lista_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        $lista_form = $em->getRepository('AcreditacionBundle:Formulario')->findAll();
        return $this->render('centro-educativo/form_dig_corr.index.html.twig',array(
        'lista_ced'=>$lista_ced,
        'lista_form'=>$lista_form,
        ));
    }
    
    /**
     * @Security("has_role('ROLE_REVISOR')")
     */
    public function form_lista_revisarAction(){
        $em = $this->getDoctrine()->getManager();
        $lista=$em->createQueryBuilder()
            ->select('fce.idFormularioPorCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
                f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
            ->andWhere('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->orderBy('e.codEstadoFormulario desc, c.codCentroEducativo, f.codFormulario')
                ->setParameter('codEstadoFormulario',array('TE','AP'))
                    ->getQuery()->getArrayResult();
        return $this->render('centro-educativo/form_lista_revisar.index.html.twig',array(
            'lista'=>$lista    
        ));
    }
    
    /**
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function form_lista_evaluarAction(){
        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $lista=$em->createQueryBuilder()
        ->select('fce.idFormularioPorCentroEducativo,  fce.estadoCriterioCentroEducativo, YEAR(fce.fechaAplicacion) as fechaAplicacion,
        c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
        f.idFormulario,f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
            ->where('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->orderBy('e.codEstadoFormulario desc, c.codCentroEducativo, f.codFormulario')
                ->setParameter('codEstadoFormulario',array('AP','CA'))
                    ->getQuery()->getArrayResult();
        return $this->render('centro-educativo/form_lista_evaluar.index.html.twig',array(
            'lista'=>$lista    
        ));
    }

    /**
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function digitarCorregirCargarAction(Request $request){
        $idCentroEducativo=$request->get('centrosEducativos');
        $idFormulario=$request->get('formularios');
        $lugarAplicacion=$request->get('lugarAplicacion');
        $fechaAplicacion=$request->get('fechaAplicacion');
        $fechaAplicacionObj=new \DateTime($fechaAplicacion);

        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $existeFormulario=$em->createQueryBuilder()
            ->select('fce.idFormularioPorCentroEducativo')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->where('fce.idFormulario=:idFormulario')
            ->andWhere('fce.idCentroEducativo=:idCentroEducativo')
            ->andWhere('YEAR(fce.fechaAplicacion)=:anioAplicacion')
                ->setParameter('idFormulario',$idFormulario)
                ->setParameter('idCentroEducativo',$idCentroEducativo)
                ->setParameter('anioAplicacion',$fechaAplicacionObj->format('Y'))
                    ->getQuery()->getArrayResult();
        if(count($existeFormulario)==0){

            $formularioPorCentroEducativo=new FormularioPorCentroEducativo();
            $formularioPorCentroEducativo->setIdCentroEducativo($em->getRepository('AcreditacionBundle:CentroEducativo')->find($idCentroEducativo));
            
            $formularioPorCentroEducativo->setIdFormulario($em->getRepository('AcreditacionBundle:Formulario')->find($idFormulario));
            $formularioPorCentroEducativo->setLugarAplicacion($lugarAplicacion);
            $formularioPorCentroEducativo->setFechaAplicacion(new \DateTime($fechaAplicacion));
            $formularioPorCentroEducativo->setIdUsuarioDigita($this->getUser());
            $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                'codEstadoFormulario' => 'NU',
            )));
            new AccionPorUsuario($em,$this->getUser(),'DF',$formularioPorCentroEducativo);
            $em->persist($formularioPorCentroEducativo);
            $em->flush();

            $session->set('idFormularioPorCentroEducativo', $formularioPorCentroEducativo->getIdFormularioPorCentroEducativo());
           
            return $this->redirectToRoute('seccion_index');

        }
        else{
            $session->getFlashBag()->add('error','El formulario ya existe para el centro educativo seleccionado.');
            return $this->redirectToRoute('centro_educativo_form_dig_corr');
        }
    }
    
    
    /*
    ----------------------------------------------------------------------------
    Gestión de cuotas
    ----------------------------------------------------------------------------    
    */

    /**
     * Lista
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function cuotasAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        $annio= $request->get('anno');
        if((isset($annio)) && (!empty($annio))){
            $anno= $request->get('anno');
        }else{
            $anno=date('Y');
        }
        $centro_escolar_id= $ce_show->getIdCentroEducativo();
        
        $resanno=array();
        $resanno=$em->createQueryBuilder()
            ->select('cagece.anno')->distinct()
            ->from('AcreditacionBundle:GradoEscolarPorCentroEducativo', 'gece')
            ->join('gece.cuotasAnualesPorGradoEscolarPorCentroEducativo','cagece')
            ->where('gece.idCentroEducativo = :id')
            ->orderBy('cagece.anno', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
            
        $res=$em->createQueryBuilder()
        ->select(
            'cagece.idCuotaAnualPorGradoEscolarPorCentroEducativo,cagece.anno, cagece.matricula,cagece.monto, cagece.cantidadCuotas,
            ne.nbrNivelEducativo, 
            ge.nbrGradoEscolar
        ')
        ->from('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo', 'cagece')
            ->join('cagece.idGradoEscolarPorCentroEducativo','gece')
            ->join('gece.idCentroEducativo','ce')
            ->join('gece.idGradoEscolar','ge')
            ->join('ge.idNivelEducativo','ne')
                ->where('ce.idCentroEducativo = :id')
                ->andWhere('cagece.anno = :anno')
                ->setParameter('id', $id)
                ->setParameter('anno', $anno)
                ->getQuery()->getResult();
                
        return $this->render('centro-educativo/cuotas.index.html.twig',array(
            'lista'=>$res,
            'ce_show'=>$ce_show,
            'anno'=>$anno,
            'lista_anno'=>$resanno,
            'debug'=>true
        ));
    }
    
    /**
     * Borrar
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function borrar_cuotaAction($id,$idcuota){
        $em = $this->getDoctrine()->getManager();
        $check_cagece = $em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->find($idcuota);
        new AccionPorUsuario($em,$this->getUser(),'ED',$check_cagece);
        $em->remove($check_cagece);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_cuotas', 
            array(
                'id'=> $id   
            )
        );
    }
    
    /**
     * Nueva
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function form_cuotaAction(Request $request,  $id){
        $em = $this->getDoctrine()->getManager();
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        $nivelxgrado = $em->getRepository('AcreditacionBundle:NivelEducativo')->findAll();
        return $this->render('centro-educativo/form_cuotas.index.html.twig',
            array(
                'ce_show'=>$ce_show,
                'nivelxgrado'=>$nivelxgrado
            )
        );
    }

    /**
     * Guardar
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function guardarcuotaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $show_ce=$request->get('idce');
        $idce=$request->get('idce');
        $idce = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($idce);
        $grado=$request->get('grado');
        $idgrado=$request->get('grado');
        $matricula=$request->get('matricula');
        $cuota=$request->get('cuota');
        $anno=$request->get('anno');
        $cantidadCuotas=$request->get('cantidadCuotas');
        $grado = $em->getRepository('AcreditacionBundle:GradoEscolar')->find($grado);
        
        $existe=$em->createQueryBuilder()
        ->select('gece.idGradoEscolarPorCentroEducativo')
        ->from('AcreditacionBundle:GradoEscolarPorCentroEducativo', 'gece')
            ->where('gece.idCentroEducativo = :idce')
            ->andWhere('gece.idGradoEscolar = :idgrado')
            ->setParameter('idce', $show_ce)
            ->setParameter('idgrado', $idgrado)
            ->getQuery()->getResult();
         $count=count($existe);
        
        if($count>="1"){
            foreach ($existe as $value) {
                $id= $value['idGradoEscolarPorCentroEducativo'];
            }
        }else{
            $ce=new GradoEscolarPorCentroEducativo();
            $ce->setIdCentroEducativo($idce);
            $ce->setIdGradoEscolar($grado);
            $ce->setActivo('S');
            $em=$this->getDoctrine()->getManager();
            $em->persist($ce);
            $em->flush();
            $id= $ce->getIdGradoEscolarPorCentroEducativo();
        }
        
         $id = $em->getRepository('AcreditacionBundle:GradoEscolarPorCentroEducativo')->find($id);
        
        $cagece=new CuotaAnualPorGradoEscolarPorCentroEducativo();
        $cagece->setIdGradoEscolarPorCentroEducativo($id);
        $cagece->setAnno($anno);
        $cagece->setMatricula($matricula);
        $cagece->setMonto($cuota);
        $cagece->setCantidadCuotas($cantidadCuotas);
        new AccionPorUsuario($em,$this->getUser(),'AD',$cagece);
        $em->persist($cagece);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_cuotas', 
            array(
                'id'=> $show_ce   
            )
        );
    }
    
    /**
     * Editar
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function editar_cuotaAction(Request $request,  $id, $idcuota){
        if (!$idcuota) {
            throw $this->createNotFoundException('No se encuentra el ID = '.$idcuota);
        }  
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->find($idcuota);
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        
        
        $grado=$em->createQueryBuilder()
        ->select(
            'cagece.idCuotaAnualPorGradoEscolarPorCentroEducativo,cagece.anno, cagece.matricula,cagece.monto,
            ne.nbrNivelEducativo, 
            ge.nbrGradoEscolar
        ')
        ->from('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo', 'cagece')
            ->join('cagece.idGradoEscolarPorCentroEducativo','gece')
            ->join('gece.idCentroEducativo','ce')
            ->join('gece.idGradoEscolar','ge')
            ->join('ge.idNivelEducativo','ne')
                ->where('ce.idCentroEducativo = :id')
                ->andWhere('cagece.idCuotaAnualPorGradoEscolarPorCentroEducativo = :idCAGECE')
                ->setParameter('id', $id)
                ->setParameter('idCAGECE', $idcuota)
                ->getQuery()->getSingleResult();
        
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to and Userentity.'); 
        }
        $editForm = $this->createForm(new CuotaAnualPorGradoEscolarPorCentroEducativoType($em), $entity);
         $editForm->handleRequest($request);
         if ($editForm->isSubmitted()) {
            $matricula = $editForm->get('matricula')->getData();
            
            $monto = $editForm->get('monto')->getData();
            
            $anno = $editForm->get('anno')->getData();
            $cantidadCuotas=$editForm->get('cantidadCuotas')->getData();
            
            $entity->setMatricula($matricula);
            $entity->setMonto($monto);
            $entity->setAnno($anno);
            $entity->setCantidadCuotas($cantidadCuotas);
            
            $em = $this->getDoctrine()->getManager();
            new AccionPorUsuario($em,$this->getUser(),'MD',$entity);
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('centro_educativo_cuotas', array(
                'id'=>$id,
                'anno'=>$anno,
                ));
        }
        return $this->render('centro-educativo/form_cuotas_edit.index.html.twig', 
            array(
                'ce_show'=>$ce_show,
                'grado'=>$grado,
                'form' => $editForm->createView(),
            ));
    }
    
    /**
     * Cuota centro educativo por archivo
     *
     * @Security("has_role('ROLE_MINED')")
     */
    public function formCuotaArchivoAction(Request $request,  $id){
        $em = $this->getDoctrine()->getManager();
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        return $this->render('centro-educativo/form_cuotas_archivo.index.html.twig',
            array(
                'ce_show'=>$ce_show
            )
        );
    }
    
    /**
     * @Security("has_role('ROLE_MINED')")
     */
    public function formCuotaArchivoCargarAction(Request $request){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $idCentroEducativo= $request->get('id_centro_educativo');
        $centroEducativo = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($idCentroEducativo);

        if($idCentroEducativo && isset($_FILES['archivo'])){
            $nbrArchivoPts=explode('.',$_FILES['archivo']['name']);
            $extArchivo=array_pop($nbrArchivoPts);
            $arrExt=array('.ods','.xls','.xlsx',);
            if(in_array('.' . strtolower($extArchivo),$arrExt)){

                switch ('.' . strtolower($extArchivo)) {
                    case '.ods':
                        $objReader = PHPExcel_IOFactory::createReader('OOCalc');
                        break;
                    case '.xls':
                        $objReader = PHPExcel_IOFactory::createReader('Excel5');
                        break;
                    case '.xlsx':
                        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                        break;
                }
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($_FILES['archivo']['tmp_name']);
                $objPHPExcel->setActiveSheetIndex(0);

                $cntError=0;
                $maxErrores=3;
                $wsIterator = $objPHPExcel->getWorksheetIterator();
                foreach($wsIterator as $worksheet){
                    $fila=array();
                    $idxHoja = $wsIterator->key();
                    foreach ($worksheet->getRowIterator() as $row){
                        $cellIterator = $row->getCellIterator();
                        foreach ($cellIterator as $cell){
                            $coord=$cell->getCoordinate();
                            $value=$cell->getCalculatedValue();
                            $fila[preg_replace('/[0-9]+/', '', $coord)]=$value;
                            //error_log("$idxHoja - $coord - $value");
                        }

                        if($cntError>=$maxErrores){
                            break 2;
                        }

                        $anio=(isset($fila['A'])?$fila['A']:null);
                        $grado=(isset($fila['B'])?$fila['B']:null);
                        $opcion=(isset($fila['C'])?$fila['C']:null);
                        $matricula=(isset($fila['D'])?$fila['D']:null);
                        $cuota=(isset($fila['E'])?$fila['E']:null);
                        $cantCuotas=(isset($fila['F'])?$fila['F']:null);

                        if(strtoupper($anio)==strtoupper('Año')){
                            continue;
                        }
                        if(!$anio || !$grado || !$matricula || !$cuota || !$cantCuotas){
                            continue;
                        }

                        $errorArr=array();
                        $filaIdx=preg_replace('/[A-Z]+/', '', $coord);

                        if(!is_numeric($anio) || $anio<1900 || $anio>date('Y')+1){
                            $errorArr[]='el año "' . $anio . '" no es válido';
                        }

                        $idGradoEscolar=null;
                        if(is_numeric($grado)){
                            $idGradoEscolar = $em->getRepository('AcreditacionBundle:GradoEscolar')->find($grado);
                        }
                        elseif(strlen($grado)==3){
                            $idGradoEscolar = $em->getRepository('AcreditacionBundle:GradoEscolar')->findOneByCodGradoEscolar(strtoupper(trim($grado)));
                        }
                        else{
                            $resGrados = $em->createQueryBuilder()
                                ->select('g.idGradoEscolar')
                                ->from('AcreditacionBundle:GradoEscolar', 'g')
                                ->where('UPPER(g.nbrGradoEscolar) = UPPER(:nbrGradoEscolar)')
                                    ->setParameter('nbrGradoEscolar', trim($grado))
                                        ->getQuery()->getResult();
                            foreach ($resGrados as $regGrado) {
                                $idGradoEscolar = $em->getRepository('AcreditacionBundle:GradoEscolar')->find($regGrado['idGradoEscolar']);
                                break;
                            }
                        }
                        if(!$idGradoEscolar){
                            $errorArr[]='no se encontró el grado escolar "' . $grado . '"';
                        }

                        if(!is_numeric($matricula) || $matricula<0){
                            $errorArr[]='el monto de la matrícula "' . $matricula . '" no es válido';
                        }
                        if(!is_numeric($cuota) || $cuota<0){
                            $errorArr[]='el monto de la cuota "' . $cuota . '" no es válido';
                        }
                        if(!is_numeric($cantCuotas) || $cantCuotas<10 || $cantCuotas>12){
                            $errorArr[]='la cantidad de cuotas "' . $cantCuotas . '" no es válida';
                        }

                        if(count($errorArr)){
                            $session->getFlashBag()->add('error','Hoja ' . ($idxHoja+1) . ', fila ' . $filaIdx . ': ' . implode(' / ',$errorArr));
                            $cntError++;
                        }
                        else{

                            $resGradosQuery = $em->createQueryBuilder()
                                ->select('g.idGradoEscolarPorCentroEducativo')
                                ->from('AcreditacionBundle:GradoEscolarPorCentroEducativo', 'g')
                                ->where('g.idCentroEducativo=:idCentroEducativo')
                                ->andWhere('g.idGradoEscolar=:idGradoEscolar')
                                    ->setParameter('idCentroEducativo', $idCentroEducativo)
                                    ->setParameter('idGradoEscolar', $idGradoEscolar);
                            if($opcion){
                                $resGradosQuery
                                    ->andWhere('UPPER(g.opcionGradoEscolar) = UPPER(:opcionGradoEscolar)')
                                        ->setParameter('opcionGradoEscolar', trim($opcion));
                            }
                            $resGrados=$resGradosQuery
                                ->getQuery()->getResult();
                            $idGradoEscolarPorCentroEducativo=null;
                            foreach ($resGrados as $regGrado) {
                                $idGradoEscolarPorCentroEducativo = $em->getRepository('AcreditacionBundle:GradoEscolarPorCentroEducativo')->find($regGrado['idGradoEscolarPorCentroEducativo']);
                                break;
                            }
                            if(!$idGradoEscolarPorCentroEducativo){
                                $idGradoEscolarPorCentroEducativo=new GradoEscolarPorCentroEducativo();
                                $idGradoEscolarPorCentroEducativo->setIdCentroEducativo($centroEducativo);
                                $idGradoEscolarPorCentroEducativo->setIdGradoEscolar($idGradoEscolar);
                                $idGradoEscolarPorCentroEducativo->setOpcionGradoEscolar(trim($opcion));
                                $idGradoEscolarPorCentroEducativo->setActivo('S');
                                $em->persist($idGradoEscolarPorCentroEducativo);
                            }

                            $idCuotaAnualPorGradoEscolarPorCentroEducativo=$em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->findOneBy(array(
                                    'idGradoEscolarPorCentroEducativo' => $idGradoEscolarPorCentroEducativo,
                                    'anno' => $anio,
                                ));
                            if(!$idCuotaAnualPorGradoEscolarPorCentroEducativo){
                                $idCuotaAnualPorGradoEscolarPorCentroEducativo=new CuotaAnualPorGradoEscolarPorCentroEducativo();
                                $idCuotaAnualPorGradoEscolarPorCentroEducativo->setIdGradoEscolarPorCentroEducativo($idGradoEscolarPorCentroEducativo);
                                $idCuotaAnualPorGradoEscolarPorCentroEducativo->setAnno($anio);
                                $accionPorUsuario='AD';
                            }
                            else{
                                $accionPorUsuario='MD';
                            }
                            $idCuotaAnualPorGradoEscolarPorCentroEducativo->setMatricula($matricula);
                            $idCuotaAnualPorGradoEscolarPorCentroEducativo->setMonto($cuota);
                            $idCuotaAnualPorGradoEscolarPorCentroEducativo->setCantidadCuotas($cantCuotas);
                            new AccionPorUsuario($em,$this->getUser(),$accionPorUsuario,$idCuotaAnualPorGradoEscolarPorCentroEducativo);
                            $em->persist($idCuotaAnualPorGradoEscolarPorCentroEducativo);

                        }

                    }
                }

                if($cntError>=$maxErrores){
                    $session->getFlashBag()->add('error','Se alcanzó la cantidad límite de errores; no se cargó ninguna cuota.');
                }
                else{
                    $em->flush();
                }

            }
            else{
                $session->getFlashBag()->add('error','Debe cargar un archivo de un tipo permitido (' . implode(', ',$arrExt) . ').');
            }
        }
        else{
            $session->getFlashBag()->add('error','No se encontró el archivo cargado.');
        }

        return $this->redirect($this->generateUrl('centro_educativo_cuotas', array(
            'id' => $idCentroEducativo,
        )));
    }
    
    /*Fin*/

     /*
    ----------------------------------------------------------------------------
    Gestión de observaciones de criterios por centro educativo
    ----------------------------------------------------------------------------    
    */

    /**
     * Lista
     *
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function observacionesAction(Request $request, $id,$form,$temp){
        $em = $this->getDoctrine()->getManager();
        $criterio=$em->createQueryBuilder()
        ->select('
            f_x_ce.idFormularioPorCentroEducativo,
            ce.codCentroEducativo,
            ce.nbrCentroEducativo,
            form.nbrFormulario,
            secc.idSeccion,
            secc.nbrSeccion
        ')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'f_x_ce')
            ->join('f_x_ce.idCentroEducativo','ce')
            ->join('f_x_ce.idFormulario','form')
            ->join('form.secciones','secc')
            ->where('ce.idCentroEducativo = :id')
                ->andWhere('exists (
                        select 1
                        from AcreditacionBundle:Pregunta p
                        where p.ponderacionMaxima is not null
                        and p.idSeccion=secc.idSeccion
                    )')
                ->andWhere('form.idFormulario = :idform')
                ->setParameter('id', $id)
                ->setParameter('idform', $form)
                ->getQuery()->getResult();
                
        
        
        return $this->render('centro-educativo/observaciones.index.html.twig',array(
            'criterio'=>$criterio,
            'temp'=>$temp,
            'debug'=>true
        ));
    }
    
    
    /**
     * Guardar
     *
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function observaciones_guardarAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $btn_accion= $request->get('btn_guardar');
   
        
        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativo');
        $idFormularioPorCentroEducativo = $em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        
       $estado="TER";
        if($btn_accion=="borrador"){
            $estado="EDIT";
        }
          
       
        $idFormularioPorCentroEducativo->setestadoCriterioCentroEducativo($estado);
        $em->persist($idFormularioPorCentroEducativo);
           
        
        
        $g_observacion=$request->get('g_observacion');
        $idSecccion=$request->get('idSeccion');
        foreach ($g_observacion as $idseccion=> $g) {
            $idseccion = $em->getRepository('AcreditacionBundle:Seccion')->find($idseccion);
            $form=new SeccionPorFormularioPorCentroEducativo();
            $form->setidFormularioPorCentroEducativo($idFormularioPorCentroEducativo);
            $form->setobservacion($g);
            $form->setidSeccion($idseccion);
            new AccionPorUsuario($em,$this->getUser(),'AO',$form);
            $em->persist($form);
        }               
        $em->flush();
        $temp=$request->get('temp');
        if($temp=="no"){
            return $this->redirectToRoute('centro_educativo_form_lista_evaluar');
        }else{
            return $this->redirectToRoute('centro_educativo_registrar_acreditacion');
        }
    }
    
    
    /**
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function observaciones_verAction(Request $request, $id,$form){
        
       
       $em = $this->getDoctrine()->getManager();
        $criterio=$em->createQueryBuilder()
        ->select('
            f_x_ce.idFormularioPorCentroEducativo,
            ce.codCentroEducativo,
            ce.nbrCentroEducativo,
            sec_form_ce.observacion,
            form.nbrFormulario,
            secc.idSeccion,
            secc.nbrSeccion,
            form.idFormulario
            
        ')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'f_x_ce')
            ->join('f_x_ce.idCentroEducativo','ce')
            ->join('f_x_ce.seccionesPorFormularioPorCentroEducativo','sec_form_ce')
            ->join('f_x_ce.idFormulario','form')
            ->join('sec_form_ce.idSeccion','secc')
            ->where('ce.idCentroEducativo = :id')
                ->andWhere('exists (
                        select 1
                        from AcreditacionBundle:Pregunta p
                        where p.ponderacionMaxima is not null
                        and p.idSeccion=secc.idSeccion
                    )')
                ->andWhere('form.idFormulario = :idform')
                ->setParameter('id', $id)
                ->setParameter('idform', $form)
                ->getQuery()->getResult();
                
        
        
        return $this->render('centro-educativo/observaciones_ver.index.html.twig',array(
            'criterio'=>$criterio,
            'debug'=>true
        ));
    }
    
    /**
     * Editar
     *
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function observaciones_editarAction(Request $request, $id,$form,$temp){
    $em = $this->getDoctrine()->getManager();
    $criterio=$em->createQueryBuilder()
    ->select(
    '
        f_x_ce.idFormularioPorCentroEducativo,
        ce.codCentroEducativo,
        ce.nbrCentroEducativo,
        sec_form_ce.idSeccionPorFormularioPorCentroEducativo,
        sec_form_ce.observacion,
        form.nbrFormulario,
        secc.idSeccion,
        secc.nbrSeccion,
        form.idFormulario
    '
    )
    ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'f_x_ce')
        ->join('f_x_ce.idCentroEducativo','ce')
        ->join('f_x_ce.seccionesPorFormularioPorCentroEducativo','sec_form_ce')
        ->join('f_x_ce.idFormulario','form')
        ->join('sec_form_ce.idSeccion','secc')
        ->where('ce.idCentroEducativo = :id')
            ->andWhere('exists (
                select 1
                from AcreditacionBundle:Pregunta p
                    where p.ponderacionMaxima is not null
                        and p.idSeccion=secc.idSeccion
            )')
            ->andWhere('form.idFormulario = :idform')
            ->setParameter('id', $id)
            ->setParameter('idform', $form)
            ->getQuery()->getResult();
            $verifico=array_key_exists('0', $criterio);
            
           // $referer = $this->getRequest()->headers->get('referer');
           //echo $referer;
           //exit();
           //return $this->redirect($referer);
            
            if($verifico==false){
                 return $this->redirectToRoute('centro_educativo_criterio_observaciones', array('id' => $id,'form'=>$form,'temp'=>$temp));
            }else{
                return $this->render('centro-educativo/observaciones_editar.index.html.twig',array(
                    'criterio'=>$criterio,
                    'temp'=>$temp,
                    'debug'=>true
                ));
            }
    }
    
    
    /**
     * Guardar editar
     *
     * @Security("has_role('ROLE_COORDINADOR')")
     */
     public function observaciones_editar_guardarAction(Request $request){
         
        $em = $this->getDoctrine()->getManager();
        $btn_accion= $request->get('btn_guardar');
        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativo');
     
        $idFormularioPorCentroEducativo = $em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $estado="TER";
        if($btn_accion=="borrador"){
            $estado="EDIT";
        }
        $idFormularioPorCentroEducativo->setestadoCriterioCentroEducativo($estado);
        $em->persist($idFormularioPorCentroEducativo);
           
        
        
        $g_observacion=$request->get('g_observacion');
        $idSecccion=$request->get('idSeccion');
        foreach ($g_observacion as $idseccion=> $g) {
            
           
            $idseccion = $em->getRepository('AcreditacionBundle:SeccionPorFormularioPorCentroEducativo')->find($idseccion);
            $idseccion->setobservacion($g);
           
            new AccionPorUsuario($em,$this->getUser(),'MO',$idseccion);
            $em->persist($idseccion);
        }
       
      
       
                $em->flush();
                
                
        $temp=$request->get('temp');
        if($temp=="no"){
            return $this->redirectToRoute('centro_educativo_form_lista_evaluar');
        }else{
            return $this->redirectToRoute('centro_educativo_registrar_acreditacion');
        }
                
        
        
    }

    /**
     * @Security("has_role('ROLE_ACREDITADOR')")
     */
    public function registrarAcreditacionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()
            ->addCustomDatetimeFunction('YEAR', 'AcreditacionBundle\DQL\YearFunction');
        $em->getConfiguration()
            ->addCustomDatetimeFunction('ROUND', 'AcreditacionBundle\DQL\RoundFunction');

        $formulariosCalificados=$em->createQueryBuilder()
            ->select('fce.idFormularioPorCentroEducativo, c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo, f.idFormulario,f.codFormulario, f.nbrFormulario, e.codEstadoFormulario, e.nbrEstadoFormulario, ROUND(sum(v.ponderacionGanada)/100,2) as ponderacionGanada,
                case
                    when e.codEstadoFormulario=\'DC\' and exists (
                        select 1
                        from AcreditacionBundle:Acreditacion a, AcreditacionBundle:EstadoAcreditacion ea
                        where YEAR(a.fechaRegistro)=YEAR(fce.fechaAplicacion)
                        and ea.codEstadoAcreditacion in (\'AC\',\'AO\')
                        and a.idEstadoAcreditacion=ea.idEstadoAcreditacion
                        and a.idCentroEducativo=c.idCentroEducativo
                    ) then \'A\'
                    when e.codEstadoFormulario=\'DC\' and exists (
                        select 1
                        from AcreditacionBundle:Acreditacion a2, AcreditacionBundle:EstadoAcreditacion ea2
                        where YEAR(a2.fechaRegistro)=YEAR(fce.fechaAplicacion)
                        and ea2.codEstadoAcreditacion = \'NA\'
                        and a2.idEstadoAcreditacion=ea2.idEstadoAcreditacion
                        and a2.idCentroEducativo=c.idCentroEducativo
                    ) then \'N\'
                    else \'\'
                end as acreditado, YEAR(fce.fechaAplicacion) as fechaAplicacion')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.formulariosPorCentroEducativoSeccionPonderacion','v')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->where('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->andWhere('not exists (
                select 1
                from AcreditacionBundle:FormularioPorCentroEducativo fce2, AcreditacionBundle:EstadoFormulario e2
                where e2.codEstadoFormulario not in (:codEstadoFormulario)
                and fce2.idEstadoFormulario=e2.idEstadoFormulario
                and fce2.idCentroEducativo=fce.idCentroEducativo
            )')
            ->groupBy('fce.idFormularioPorCentroEducativo, c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo, f.codFormulario, f.nbrFormulario, e.codEstadoFormulario, e.nbrEstadoFormulario')
                ->setParameter('codEstadoFormulario',array('CA','DC'))
                ->getQuery()->getArrayResult();

        $noAcreditado=$em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findOneByCodEstadoAcreditacion('NA');
        $acreditadoConObservaciones=$em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findOneByCodEstadoAcreditacion('AO');
        $acreditado=$em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findOneByCodEstadoAcreditacion('AC');
        $arrayLimites=array(
            'min' => $noAcreditado->getNotaMinima(),
            'med1' => $acreditadoConObservaciones->getNotaMinima(),
            'med2' => $acreditadoConObservaciones->getNotaMaxima(),
            'max' => $acreditado->getNotaMaxima(),
            'margen' => $this->margenNota,
        );

        $nFormulariosCalificados=array();
        foreach ($formulariosCalificados as $formularioCalificado) {
            if(!isset($nFormulariosCalificados[$formularioCalificado['idCentroEducativo']])){
                $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]=$formularioCalificado;
            }
            if($formularioCalificado['codFormulario']=='F1P'){
                $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]['puntuacionParvularia']=$formularioCalificado['ponderacionGanada'];
            }
            elseif($formularioCalificado['codFormulario']=='F1'){
                $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]['puntuacionBasica']=$formularioCalificado['ponderacionGanada'];
            }
        }

        return $this->render('centro-educativo/acreditar.index.html.twig',array(
            'centrosEducativos' => $nFormulariosCalificados,
            'arrayLimites' => $arrayLimites,
        ));
    }

    /**
     * @Security("has_role('ROLE_ACREDITADOR')")
     */
    public function acreditarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $centros=array();
        foreach ($request->request->all() as $key => $value) {
            if(strpos($key,'dictamen')===0){
                $centros[substr($key,8)]=$value;
            }
            elseif($key=='fechaInicio'){
                $fechaInicio=new \DateTime($value);
            }
        }
        foreach ($centros as $idCentroEducativo => $codEstadoAcreditacion) {
            $centroEducativo=$em->getRepository('AcreditacionBundle:CentroEducativo')->find($idCentroEducativo);
            $estadoAcreditacion=$em->getRepository('AcreditacionBundle:EstadoAcreditacion')->findOneBy(array('codEstadoAcreditacion' => $codEstadoAcreditacion));
            $fechaFin=clone $fechaInicio;
            $acreditacion=new Acreditacion();
            $acreditacion->setIdCentroEducativo($centroEducativo);
            $acreditacion->setIdEstadoAcreditacion($estadoAcreditacion);
            $acreditacion->setFechaInicio($fechaInicio);
            $acreditacion->setFechaFin($fechaFin->add(new \DateInterval('P' . $estadoAcreditacion->getAniosVigencia() . 'Y')));
            $acreditacion->setFechaRegistro(new \DateTime());
            new AccionPorUsuario($em,$this->getUser(),'RA',$acreditacion);
            $em->persist($acreditacion);

            $estadoFormulario=$em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array('codEstadoFormulario' => 'DC'));
            $em->createQueryBuilder()
                ->update('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
                ->set('fce.idEstadoFormulario',':idEstadoFormulario')
                ->where('fce.idCentroEducativo=:centroEducativo')
                ->andWhere('exists (
                    select 1
                    from AcreditacionBundle:EstadoFormulario e
                    where e.codEstadoFormulario=\'CA\'
                    and e.idEstadoFormulario=fce.idEstadoFormulario
                )')
                    ->setParameter('idEstadoFormulario', $estadoFormulario)
                    ->setParameter('centroEducativo', $centroEducativo)
                        ->getQuery() ->execute();
        }
        $em->flush();

        return $this->redirectToRoute('centro_educativo_registrar_acreditacion');
    }
    
    
    
    /**
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function ArchivoInstrumentoAction(Request $request)
    {
        return $this->render('centro-educativo/form_archivo.index.html.twig');
    }
    
    
    
    /**
     * Cargar archivo al instrumento
     *
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function CargarArchivoInstrumentoAction(Request $request){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $uploadPath='fce/';
        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);

        if($formularioPorCentroEducativo && isset($_FILES['archivo'])){

            $nbrArchivoPts=explode('.',$_FILES['archivo']['name']);
            $extArchivo=array_pop($nbrArchivoPts);
            $arrExt=array('.pdf',);
            if(in_array('.' . strtolower($extArchivo),$arrExt)){

                $tmp_name = $_FILES['archivo']['tmp_name'];
                $name = basename($tmp_name);
                if(!is_dir($this->uploadDir . '/' . $uploadPath)){
                    mkdir($this->uploadDir . '/' . $uploadPath);
                }
                while(is_file($this->uploadDir . '/' . $uploadPath . '/' . $name)){
                    $name.=rand();
                }
                move_uploaded_file($tmp_name, $this->uploadDir . '/' . $uploadPath . '/' . $name);

                $formularioPorCentroEducativo->setNbrArchivo($_FILES['archivo']['name']);
                $formularioPorCentroEducativo->setRutaArchivo($uploadPath . '/' . $name);
                $em->persist($formularioPorCentroEducativo);
                $em->flush();

                $session->getFlashBag()->add('info','Archivo cargado exitosamente.');

            }
            else{
                $session->getFlashBag()->add('error','Debe cargar un archivo de un tipo permitido (' . implode(', ',$arrExt) . ').');
            }
        }
        else{
            $session->getFlashBag()->add('error','No se encontró el archivo cargado.');
        }

        return $this->redirect($this->generateUrl('seccion_index'));
    }

    /**
     * Cargar archivo al instrumento
     *
     * @Security("has_role('ROLE_DIGITADOR') or has_role('ROLE_REVISOR') or has_role('ROLE_COORDINADOR') or has_role('ROLE_ACREDITADOR')")
     */
    public function descargarArchivoInstrumentoAction(Request $request){

        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $idFormularioPorCentroEducativoRevisar=$request->get('idFormularioPorCentroEducativoRevisar');

        if($idFormularioPorCentroEducativo){
            $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        }
        elseif($idFormularioPorCentroEducativoRevisar){
            $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativoRevisar);
        }

        $response = new response();
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $formularioPorCentroEducativo->getNbrArchivo()
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        $response->sendHeaders();
        $response->setContent(file_get_contents($this->uploadDir . '/' . $formularioPorCentroEducativo->getRutaArchivo()));

        return $response;
    }

}
