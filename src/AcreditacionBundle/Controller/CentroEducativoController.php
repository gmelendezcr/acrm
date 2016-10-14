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
        ->select('fce.idFormularioPorCentroEducativo,  fce.estadoCriterioCentroEducativo,
        c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
        f.idFormulario,f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
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

     /*
    ----------------------------------------------------------------------------
    Gestión de observaciones de criterios por centro educativo
    ----------------------------------------------------------------------------    
    */
    //Lista
    public function observacionesAction(Request $request, $id,$form){
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
                
        //var_dump($criterio);
        //exit();
        
        
        return $this->render('centro-educativo/observaciones.index.html.twig',array(
            'criterio'=>$criterio,
            'debug'=>true
        ));
    }
    
    
    //Guardar
    public function observaciones_guardarAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
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
            $em->persist($form);
        }
       
      
       
                $em->flush();
        return $this->redirectToRoute('centro_educativo_form_lista_evaluar');
        
    }
    
    
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
            //->join('form.secciones','secc')
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
                //->groupBy('secc.idSeccion')
                ->getQuery()->getResult();
                
        //var_dump($criterio);
        //exit();
        
        
        return $this->render('centro-educativo/observaciones_ver.index.html.twig',array(
            'criterio'=>$criterio,
            'debug'=>true
        ));
    }
    
    //Editar
    public function observaciones_editarAction(Request $request, $id,$form){
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
        //->join('form.secciones','secc')
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
            //->groupBy('secc.idSeccion')
            ->getQuery()->getResult();
        return $this->render('centro-educativo/observaciones_editar.index.html.twig',array(
            'criterio'=>$criterio,
            'debug'=>true
        ));
    }
    
    
    //Guardar editar
     public function observaciones_editar_guardarAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
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
            //$form=new SeccionPorFormularioPorCentroEducativo();
            //$idseccion->setidFormularioPorCentroEducativo($idFormularioPorCentroEducativo);
            $idseccion->setobservacion($g);
           
            $em->persist($idseccion);
        }
       
      
       
                $em->flush();
        return $this->redirectToRoute('centro_educativo_form_lista_evaluar');
        
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
$versionParaCoordinador=true;
$versionParaCoordinador=true;
$versionParaCoordinador=true;

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

    public function registrarAcreditacionAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $formulariosCalificados=$em->createQueryBuilder()
            ->select('fce.idFormularioPorCentroEducativo, c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo, f.codFormulario, f.nbrFormulario, e.codEstadoFormulario, e.nbrEstadoFormulario, sum(v.ponderacionGanada)/100 as ponderacionGanada')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.formulariosPorCentroEducativoSeccionPonderacion','v')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->where('not exists (
                select 1
                from AcreditacionBundle:FormularioPorCentroEducativo fce2, AcreditacionBundle:EstadoFormulario e2
                where e2.codEstadoFormulario<>\'CA\'
                and fce2.idEstadoFormulario=e2.idEstadoFormulario
                and fce2.idCentroEducativo=fce.idCentroEducativo
            )')
            ->groupBy('fce.idFormularioPorCentroEducativo, c.idCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo, f.codFormulario, f.nbrFormulario, e.codEstadoFormulario, e.nbrEstadoFormulario')
                ->andWhere('e.codEstadoFormulario=:codEstadoFormulario')
                ->setParameter('codEstadoFormulario','CA')
                ->getQuery()->getArrayResult();

        $nFormulariosCalificados=array();
        foreach ($formulariosCalificados as $formularioCalificado) {
            $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]=$formularioCalificado;
            if($formularioCalificado['codFormulario']=='F1P'){
                $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]['puntuacionParvularia']=$formularioCalificado['ponderacionGanada'];
            }
            elseif($formularioCalificado['codFormulario']=='F1'){
                $nFormulariosCalificados[$formularioCalificado['idCentroEducativo']]['puntuacionBasica']=$formularioCalificado['ponderacionGanada'];
            }
        }

        return $this->render('centro-educativo/acreditar.index.html.twig',array(
            'centrosEducativos' => $nFormulariosCalificados
        ));
    }

    public function acreditarAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

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
            ->select('ce.codCentroEducativo, ce.nbrCentroEducativo, a.fechaInicio, a.fechaFin, e.nbrEstadoAcreditacion')
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
        $this->pdfObj->setFooterType('diplomaFooter');
        $this->pdfObj->startPageGroup();
        $this->pdfObj->AddPage('L');

        $centrado=($this->pdfObj->GetPageWidth()/2)-125;

        $this->pdfObj->SetFontSize(18);
        $this->pdfObj->SetX($centrado);
        $this->pdfObj->SetY($this->pdfObj->GetY()+25);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Por cuanto el:', 0, 'C');

        $this->pdfObj->SetFontSize(24);
        $this->pdfObj->SetX($centrado);
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), $centroEducativo['nbrCentroEducativo'], 0, 'C');
        $this->pdfObj->SetX($centrado);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'CÓDIGO ' . $centroEducativo['codCentroEducativo'], 0, 'C');

        $this->pdfObj->SetFontSize(14);
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Ha cumplido con los requisitos establecidos en el marco legal del Sistema de Acreditación.', 0, 'C');
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'Por tanto otorga al:', 0, 'C');

        $this->pdfObj->SetFontSize(18);
        $this->pdfObj->SetY($this->pdfObj->GetY()+4);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), $centroEducativo['nbrCentroEducativo'], 0, 'C');
        
        $this->pdfObj->SetFontSize(14);
        $this->pdfObj->SetY($this->pdfObj->GetY()+8);
        $this->pdfObj->writeHTMLCell(250, $this->pdfObj->getLineHeight(),$this->pdfObj->GetX(),$this->pdfObj->GetY(), 'La Presente <strong>Acreditación Institucional</strong> que lo reconoce como <strong>CENTRO EDUCATIVO PRIVADO ' . strtoupper($centroEducativo['nbrEstadoAcreditacion']) . '</strong> para el período ' . $this->pdfObj->dateLongFormat($centroEducativo['fechaInicio']->format('d/m/Y'),false,true) . ' a ' . $this->pdfObj->dateLongFormat($centroEducativo['fechaFin']->format('d/m/Y'),false,true) . '.', 0, 0, false, true, 'C');

        $this->pdfObj->SetFontSize(12);
        $this->pdfObj->SetY($this->pdfObj->GetY()+16);
        $this->pdfObj->MultiCell(250, $this->pdfObj->getLineHeight(), 'San Salvador, 19 de diciembre de 2016', 0, 'C');




        












$this->centroEducativo['codCentroEducativo']='XXX';
        $this->pdfObj->Output("diploma-" . $this->centroEducativo['codCentroEducativo'] . "-$anio.pdf", 'I');

    }
}
