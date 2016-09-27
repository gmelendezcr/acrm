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
use AcreditacionBundle\Form\CentroEducativoType;




class CentroEducativoController extends Controller{
    public function listaAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        /*$connection = $em->getConnection();
        $statement = $connection->prepare("SELECT cd.*, mpio.*, depto.*, zg.* FROM (CENTRO_EDUCATIVO cd 
        INNER JOIN MUNICIPIO mpio ON cd.ID_MUNICIPIO=mpio.ID_MUNICIPIO
        INNER JOIN DEPARTAMENTO depto ON depto.ID_DEPARTAMENTO=mpio.ID_DEPARTAMENTO
        INNER JOIN ZONA_GEOGRAFICA zg ON zg.ID_ZONA_GEOGRAFICA=depto.ID_ZONA_GEOGRAFICA
        INNER JOIN JORNADA_CENTRO_EDUCATIVO jda ON cd.ID_JORNADA_CENTRO_EDUCATIVO=jda.ID_JORNADA_CENTRO_EDUCATIVO
        INNER JOIN TAMANNO_CENTRO_EDUCATIVO tce ON cd.ID_TAMANNO_CENTRO_EDUCATIVO=tce.ID_TAMANNO_CENTRO_EDUCATIVO
        )
        ");
        $statement->execute();
        $results = $statement->fetchAll();
        */
        $res=$em->createQueryBuilder()
        ->select('ce.idCentroEducativo as ID_CENTRO_EDUCATIVO, ce.codCentroEducativo as COD_CENTRO_EDUCATIVO, ce.nbrCentroEducativo as NBR_CENTRO_EDUCATIVO,
        ce.direccionCentroEducativo as DIRECCION_CENTRO_EDUCATIVO, ce.totalAlumnos as TOTAL_ALUMNOS, 
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
        ce.direccionCentroEducativo as DIRECCION_CENTRO_EDUCATIVO, ce.totalAlumnos as TOTAL_ALUMNOS, 
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
        //$lista = $em->getRepository('AcreditacionBundle:CentroEducativo')->findAll();
        //return $this->render('centro-educativo/lista.index.html.twig');
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
    
    //Cuotas
    public function cuotasAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $ce_show = $em->getRepository('AcreditacionBundle:CentroEducativo')->find($id);
        $anno=date('Y');
        //$anno='2015';
        $res=$em->createQueryBuilder()
        ->select('cagece.anno, cagece.matricula,cagece.monto,
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
            'debug'=>true
        ));
    }
    
   
    
    public function borrarCuotasAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $check_cagece = $em->getRepository('AcreditacionBundle:CuotaAnualPorGradoEscolarPorCentroEducativo')->find($id);
        $em->remove($check_cag);
        $em->flush();
        return $this->redirectToRoute('centro_educativo_lista');
    }
    
}
