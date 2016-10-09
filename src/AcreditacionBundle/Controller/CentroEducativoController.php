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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AcreditacionBundle\Form\CentroEducativoType;
use AcreditacionBundle\Form\CuotaAnualPorGradoEscolarPorCentroEducativoType;




class CentroEducativoController extends Controller{
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
                ->getQuery()->getResult();
        return $this->render('centro-educativo/lista.index.html.twig',array(
            'lista'=>$res,
            'debug'=>true
        ));
    }
    
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
                ->orWhere('ce.totalAlumnos like :nbr')
                ->orWhere('zg.nbrZonaGeografica like :nbr')
                ->orWhere('depto.nbrDepartamento like :nbr')
                ->orWhere('mpio.nbrMunicipio like :nbr')
                ->orWhere('jda.nbrJornadaCentroEducativo like :nbr')
                ->orWhere('tce.nbrTamannoCentroEducativo like :nbr')
                ->setParameter('nbr', $nbr)
            ->getQuery()->getResult();
        return $this->render('centro-educativo/lista.index.html.twig',array(
            'lista'=>$res,
            'nbr'=>$nbrq,
            'debug'=>true
            ));
    }
    
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $departamentos = $em->getRepository('AcreditacionBundle:Departamento')->findAll();
        $zona = $em->getRepository('AcreditacionBundle:ZonaGeografica')->findAll();
        $jornadas = $em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->findAll();
        $tamannos = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->findAll();
        return $this->render('centro-educativo/addcedu.index.html.twig', array(
            'departamentos' => $departamentos,
            'zonas' =>$zona,
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
        $municipio=$request->get('municipio');
        $jornada=$request->get('jornada');
        $tamanno=$request->get('tamanno');
        $municipio = $em->getRepository('AcreditacionBundle:Municipio')->find($municipio);
        $jornada = $em->getRepository('AcreditacionBundle:JornadaCentroEducativo')->find($jornada);
        $tamanno = $em->getRepository('AcreditacionBundle:TamannoCentroEducativo')->find($tamanno);
        //$jornadas=$request->get('jornadas');
        //$tamanno=$request->get('tamanno');
        $ce=new CentroEducativo();
        $ce->setCodCentroEducativo($codigo);
        $ce->setNbrCentroEducativo($nombre);
        $ce->setDireccionCentroEducativo($direccion);
        $ce->setTotalAlumnos($total_alumnos);
        $ce->setIdMunicipio($municipio);
        $ce->setIdJornadaCentroEducativo($jornada);
        $ce->setIdTamannoCentroEducativo($tamanno);
        
        $ce->setActivo('S');
        $em=$this->getDoctrine()->getManager();
        $em->persist($ce);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_lista');
    }
    
    public function actualizarAction(Request $request){
        if ($request->getMethod() == "POST") {
            $EditForm->submit($request);
            if ($form->isValid()) {
                $postData = current($request->request->all());
                var_dump($postData); /* All post data is here */
               /* echo  $postData['students']; */
               /* echo  $postData['students2']; */
                /*
                 * do you update stuff here
                 * */
            }
       }
    }
    
    public function borrarAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $check_ced = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        $em->remove($check_ced);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_lista');
    }
    
   
    
    /*Editar datos de centros*/
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
        //if ($editForm->isSubmitted() && $editForm->isValid()) {
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
    
    public function form_dig_corrAction(){
        $session = new Session();
        $em = $this->getDoctrine()->getEntityManager();

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
        //$lista_form_estado = $em->getRepository('AcreditacionBundle:EstadoFormulario')->findAll();
        return $this->render('centro-educativo/form_dig_corr.index.html.twig',array(
        'lista_ced'=>$lista_ced,
        'lista_form'=>$lista_form,
        //'lista_form_estado'=>$lista_form_estado
        ));
    }
    
    public function form_lista_revisarAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista=$em->createQueryBuilder()
            ->select('fce.idFormularioPorCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
                f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
            ->andWhere('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->orderBy('e.codEstadoFormulario','desc')
                ->setParameter('codEstadoFormulario',array('TE','AP'))
                    ->getQuery()->getArrayResult();
        //var_dump($lista);
        return $this->render('centro-educativo/form_lista_revisar.index.html.twig',array(
            'lista'=>$lista    
        ));
    }
    
    public function form_lista_evaluarAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $lista=$em->createQueryBuilder()
        ->select('fce.idFormularioPorCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
        f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
                ->andWhere('e.codEstadoFormulario in (:codEstadoFormulario)')
                ->setParameter('codEstadoFormulario',array('AP','CA'))
                ->orderBy('e.codEstadoFormulario','desc')
                ->getQuery()->getArrayResult();
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
        $formularioPorCentroEducativo->setIdUsuarioDigita($this->getUser());
        $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
            'codEstadoFormulario' => 'NU',
        )));
        $em->persist($formularioPorCentroEducativo);
        $em->flush();

        $session = new Session();
        $session->set('idFormularioPorCentroEducativo', $formularioPorCentroEducativo->getIdFormularioPorCentroEducativo());
       
        return $this->redirectToRoute('seccion_index');
    }
    
    
    /*
    ----------------------------------------------------------------------------
    Gestión de cuotas
    ----------------------------------------------------------------------------    
    */
    //Lista
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
       
        
        $idselector=$em->createQueryBuilder()
        ->select('gece.idGradoEscolarPorCentroEducativo')->distinct()
        ->from('AcreditacionBundle:GradoEscolarPorCentroEducativo', 'gece')
            ->where('gece.idCentroEducativo = :id')
            ->setParameter('id', $centro_escolar_id)
            ->setMaxResults('1')
            ->getQuery()
            ->getResult();
            $grado_escolar_ce_id= (int)$idselector[0]['idGradoEscolarPorCentroEducativo'];
           //var_dump($idselector); 
            
        //$grado_escolar_ce_id= $idselector->getIdGradoEscolarPorCentroEducativo();
        
        
        $resanno=$em->createQueryBuilder()
        ->select('cagece.anno')->distinct()
        ->from('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo', 'cagece')
            ->where('cagece.idGradoEscolarPorCentroEducativo = :id')
            ->orderBy('cagece.anno', 'DESC')
            ->setParameter('id', $grado_escolar_ce_id)
            ->getQuery()
            ->getResult();
        
        
        
            
        $res=$em->createQueryBuilder()
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
                ->andWhere('cagece.anno = :anno')
                ->setParameter('id', $id)
                ->setParameter('anno', $anno)
                ->getQuery()->getResult();
                //var_dump($res);
         
        return $this->render('centro-educativo/cuotas.index.html.twig',array(
            'lista'=>$res,
            'ce_show'=>$ce_show,
            'anno'=>$anno,
            'lista_anno'=>$resanno,
            'debug'=>true
        ));
    }
    
    //Borrar
    public function borrar_cuotaAction($id,$idcuota){
        $em = $this->getDoctrine()->getEntityManager();
        $check_cagece = $em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->find($idcuota);
        $em->remove($check_cagece);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_cuotas', 
            array(
                'id'=> $id   
            )
        );
    }
    
    //Nueva
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
    //Guardar
    public function guardarcuotaAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $show_ce=$request->get('idce');
        $idce=$request->get('idce');
        $idce = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($idce);
        $grado=$request->get('grado');
        $idgrado=$request->get('grado');
        $matricula=$request->get('matricula');
        $cuota=$request->get('cuota');
        $anno=$request->get('anno');
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
        $em->persist($cagece);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_cuotas', 
            array(
                'id'=> $show_ce   
            )
        );
    }
    
    //Editar
    public function editar_cuotaAction(Request $request,  $id, $idcuota){
        if (!$idcuota) {
            throw $this->createNotFoundException('No se encuentra el ID = '.$idcuota);
        }  
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->find($idcuota);
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        //$ce_show=$ce_show->getnbrCentroEducativo();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to and Userentity.'); 
        }
        $editForm = $this->createForm(new CuotaAnualPorGradoEscolarPorCentroEducativoType($em), $entity);
         $editForm->handleRequest($request);
         if ($editForm->isSubmitted()) {
            $matricula = $editForm->get('matricula')->getData();
            
            $monto = $editForm->get('monto')->getData();
            
            $anno = $editForm->get('anno')->getData();
            
            $entity->setMatricula($matricula);
            $entity->setMonto($monto);
            $entity->setAnno($anno);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('centro_educativo_cuotas', array(
                'id'=>$id,
                'anno'=>$anno
                ));
        }
        
        
        
       return $this->render('centro-educativo/form_cuotas_edit.index.html.twig', 
            array(
                'ce_show'=>$ce_show,    
                'form' => $editForm->createView(),
            ));
    }
    /*Fin*/
    
}
