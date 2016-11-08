<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Seccion;
use AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo;
use AcreditacionBundle\Entity\AccionPorUsuario;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Seccion controller.
 *
 */
class SeccionController extends Controller
{
    /**
     * Lists all Seccion entities.
     *
     * @Security("has_role('ROLE_DIGITADOR') or has_role('ROLE_REVISOR') or has_role('ROLE_COORDINADOR')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $session = new Session();
        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativoRevisar');
        if($idFormularioPorCentroEducativo){
            $idFormularioPorCentroEducativoRevisar=$idFormularioPorCentroEducativo;
        }
        else{
            $idFormularioPorCentroEducativoRevisar=null;
            $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        }
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $estadoFormularioPorCentroEducativo=$formularioPorCentroEducativo
            ->getIdEstadoFormulario()->getCodEstadoFormulario();
        if($formularioPorCentroEducativo->getIdformularioPorCentroEducativo()==$session->get('idFormularioPorCentroEducativo') &&
            !in_array($formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario(),array('NU','DI','CO','RE'))){
            $idFormularioPorCentroEducativo=null;
            $session->remove('idFormularioPorCentroEducativo');
        }
        if(!$idFormularioPorCentroEducativo){
            return $this->redirectToRoute('homepage');
        }

        $resFormulario=$em->createQueryBuilder()
            ->select('f.idFormulario, f.codFormulario, f.nbrFormulario, c.codCentroEducativo, c.nbrCentroEducativo')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idFormulario','f')
            ->join('fce.idCentroEducativo','c')
            ->where('fce.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
                ->setParameter('idFormularioPorCentroEducativo',$idFormularioPorCentroEducativo)
                    ->getQuery()->getSingleResult();
        $idFormulario=$em->getRepository('AcreditacionBundle:Formulario')->find($resFormulario['idFormulario']);

        $session->set('codCentroEducativo', $resFormulario['codCentroEducativo']);
        $session->set('nbrCentroEducativo', $resFormulario['nbrCentroEducativo']);
        $session->set('codFormulario', $resFormulario['codFormulario']);
        $session->set('nbrFormulario', $resFormulario['nbrFormulario']);

        $seccions=$em->createQueryBuilder()
            ->select('s.idSeccion, s.codSeccion, s.nbrSeccion, s.descripcionSeccion, s.activo, (
                    select count(1)
                    from AcreditacionBundle:pregunta p, AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo r
                    where r.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo
                    and r.revisar=\'S\'
                    and p.idPregunta=r.idPregunta
                    and p.idSeccion=s.idSeccion
                ) as cntRevisar, (
                    select count(1)
                    from AcreditacionBundle:pregunta h, AcreditacionBundle:pregunta p1, AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo r1
                    where r1.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo
                    and r1.revisar=\'S\'
                    and h.idPreguntaPadre=p1.idPregunta
                    and h.idPregunta=r1.idPregunta
                    and p1.idSeccion=s.idSeccion
                ) as cntRevisarHija')
            ->from('AcreditacionBundle:Seccion', 's')
            ->where('s.idFormulario=:idFormulario')
                ->setParameter('idFormulario',$idFormulario)
                ->setParameter('idFormularioPorCentroEducativo',$idFormularioPorCentroEducativo)
                    ->getQuery()->getResult();

        return $this->render('seccion/index.html.twig', array(
            'seccions' => $seccions,
            'idFormularioPorCentroEducativoRevisar' => $idFormularioPorCentroEducativoRevisar,
            'estadoFormularioPorCentroEducativo' => $estadoFormularioPorCentroEducativo,
            'archivoSubido' => $formularioPorCentroEducativo->getRutaArchivo(),
        ));
    }

    /**
     * Finds and displays a Seccion entity.
     *
     * @Security("has_role('ROLE_DIGITADOR') or has_role('ROLE_REVISOR') or has_role('ROLE_COORDINADOR')")
     */
    public function showAction(Seccion $seccion, Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $idFormularioPorCentroEducativoRevisar=$request->get('idFormularioPorCentroEducativoRevisar');
        if($idFormularioPorCentroEducativoRevisar){
            $idFormularioPorCentroEducativo=$idFormularioPorCentroEducativoRevisar;
        }
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);

        $idSeccion=$seccion->getIdSeccion();
        $em = $this->getDoctrine()->getManager();
        $resps=$em->createQueryBuilder()
            ->select('p.idPregunta, t.codTipoPregunta, pp.idPregunta as idPreguntaPadre, ore.idOpcionRespuesta, rfc.revisar, rfc.valorRespuesta, rfc.ponderacionGanada, rfc.opcionNoAplica, rfc.opcionOtroTexto')
            ->from('AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo', 'rfc')
            ->join('rfc.idPregunta','p')
            ->join('p.idSeccion','s')
            ->join('p.idTipoPregunta','t')
            ->leftJoin('rfc.idOpcionRespuesta','ore')
            ->leftJoin('p.idPreguntaPadre','pp')
            ->where('rfc.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
            ->andWhere('s.idSeccion=:idSeccion')
                ->setParameter('idFormularioPorCentroEducativo',$formularioPorCentroEducativo)
                ->setParameter('idSeccion',$em->getRepository('AcreditacionBundle:Seccion')->find($idSeccion))
                    ->getQuery()->getResult();
        $respuestas=$revisar=$puntuaciones=$noAplica=$opcionOtroTexto=array();
        foreach($resps as $resp){
            if($resp['idPreguntaPadre'] && $resp['idOpcionRespuesta']){
                $respuestas[$resp['idPregunta']][$resp['idOpcionRespuesta']]=$resp['valorRespuesta'];
                $puntuaciones[$resp['idPregunta']][$resp['idOpcionRespuesta']]=$resp['ponderacionGanada'];
                if($resp['revisar']=='S'){
                    $revisar[$resp['idPregunta']][$resp['idOpcionRespuesta']]=true;
                }
            }
            else{
                $respuestas[$resp['idPregunta']]=$resp['valorRespuesta'];
                $puntuaciones[$resp['idPregunta']]=$resp['ponderacionGanada'];
                if($resp['revisar']=='S'){
                    $revisar[$resp['idPregunta']]=true;
                }
            }
            if($resp['opcionNoAplica']=='S'){
                $noAplica[$resp['idPregunta']]=true;
            }
            if($resp['opcionOtroTexto']){
                $opcionOtroTexto[$resp['idPregunta']]=$resp['opcionOtroTexto'];
            }
        }

        return $this->render('seccion/showAsForm.html.twig', array(
            'seccion' => $seccion,
            'respuestas' => $respuestas,
            'revisar' => $revisar,
            'puntuaciones' => $puntuaciones,
            'noAplica' => $noAplica,
            'opcionOtroTexto' => $opcionOtroTexto,
            'idFormularioPorCentroEducativoRevisar' => $idFormularioPorCentroEducativoRevisar,
            'estadoFormularioPorCentroEducativo' => $formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario()
        ));
    }

    /**
     * Guarda las preguntas de una sección
     *
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function guardarAction(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);

        $codEstadoFormulario=$formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(in_array($codEstadoFormulario, array('NU','DI','RE','CO'))){

            $idSeccion=$request->get('idSeccion');
            $respuestasArr=$request->request->all();
            $opcionOtroTextoArr=array();
            foreach($respuestasArr as $key => $value){
                if(preg_match('/^[A-Z]{2}_([0-9]+)_Texto$/', $key, $matches)){
                    $opcionOtroTextoArr[$matches[1]]=$value;
                }
                elseif(preg_match('/^idFila([0-9]*)_idColumna([0-9]+)_Texto$/', $key, $matches)){
                    $opcionOtroTextoArr[$matches[2]]=$value;
                }
            }
            reset($respuestasArr);
            foreach($respuestasArr as $key => $value){
                $matches=array();
                $opcionNoAplica=$opcionOtroTexto=null;
                if(in_array($key,array('idSeccion','Guardar'))){
                    continue;
                }
                elseif(preg_match('/^[A-Z]{2}_([0-9]+)$/', $key, $matches)){
                    $idOpcionRespuesta=false;
                    $idPregunta=$matches[1];
                }
                elseif(preg_match('/^idFila([0-9]*)_idColumna([0-9]+)$/', $key, $matches)){
                    $idOpcionRespuesta=$matches[1];
                    $idPregunta=$matches[2];
                }
                elseif(preg_match('/^[A-Z]{2}NoAplica_([0-9]+)$/', $key, $matches)){
                    $idPregunta=$matches[1];
                    $opcionNoAplica='S';
                    $value=null;
                }
                elseif(preg_match('/^[A-Z]{2}_([0-9]+)_Texto$/', $key, $matches) ||
                    preg_match('/^idFila([0-9]*)_idColumna([0-9]+)_Texto$/', $key, $matches)){
                    continue;
                }
                else{
                    error_log(__CLASS__ . '->' . __METHOD__ . ' (línea ' . __LINE__ . '), no se guardó: ' . "key: $key, value: $value");
                    continue;
                }
                if(isset($opcionOtroTextoArr[$idPregunta])){
                    $opcionOtroTexto=$opcionOtroTextoArr[$idPregunta];
                }
                $pregunta=$em->getRepository('AcreditacionBundle:Pregunta')->find($idPregunta);
                $resp=$em->getRepository('AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo')->findOneBy(array(
                    'idFormularioPorCentroEducativo' => $idFormularioPorCentroEducativo,
                    'idPregunta' => $idPregunta,
                    'idOpcionRespuesta' => ($idOpcionRespuesta?$idOpcionRespuesta:null),
                ));
                if(!is_object($resp)){
                    if($value . ''==='' && !$opcionNoAplica && !$opcionOtroTexto){
                        continue;
                    }
                    $resp=new RespuestaPorFormularioPorCentroEducativo();
                    $resp->setIdFormularioPorCentroEducativo($formularioPorCentroEducativo);
                    $resp->setIdPregunta($pregunta);
                    if($idOpcionRespuesta){
                        $resp->setIdOpcionRespuesta($em->getRepository('AcreditacionBundle:OpcionRespuesta')->find($idOpcionRespuesta));
                    }
                }
                else{
                    if($value.''==='' && !$opcionNoAplica && !$opcionOtroTexto){
                        $em->remove($resp);
                        continue;
                    }
                }
                $resp->setOpcionNoAplica($opcionNoAplica);
                $resp->setOpcionOtroTexto($opcionOtroTexto);
                $resp->setValorRespuesta($value);
                new AccionPorUsuario($em,$this->getUser(),'GF',$resp);
                $em->persist($resp);
            }
            if(in_array($codEstadoFormulario,array('NU','DI'))){
                $nuevoCodEstadoFormulario='DI';
            }
            else{
                $nuevoCodEstadoFormulario='CO';
            }
            $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                'codEstadoFormulario' => $nuevoCodEstadoFormulario,
            )));
            $em->persist($formularioPorCentroEducativo);
            $em->flush();
            return $this->redirectToRoute('seccion_index');
            //return $this->redirectToRoute('seccion_show', array('id' => $idSeccion));

        }
        else{
            $session->getFlashBag()->add('error','El estado del formulario no es el correcto para guardar respuestas.');
        }
        
    }

    /**
     * @Security("has_role('ROLE_DIGITADOR')")
     */
    public function terminarAction(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $codEstadoFormulario=$formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(in_array($codEstadoFormulario,array('DI','RE','CO'))){



            $seccSinRespuesta=$em->createQueryBuilder()
                ->select('s.codSeccion, s.nbrSeccion, fce.nbrArchivo, fce.rutaArchivo')
                ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
                ->join('fce.idFormulario','f')
                ->join('f.secciones','s')
                ->where('fce.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
                ->andWhere('not exists (
                    select 1
                    from AcreditacionBundle:Pregunta p, AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo r
                    where r.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo
                    and p.idPregunta=r.idPregunta
                    and p.idSeccion=s.idSeccion
                )')
                ->andWhere('not exists (
                    select 1
                    from AcreditacionBundle:Pregunta h, AcreditacionBundle:Pregunta p1, AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo r1
                    where r1.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo
                    and h.idPreguntaPadre=p1.idPregunta
                    and h.idPregunta=r1.idPregunta
                    and p1.idSeccion=s.idSeccion
                )')
                    ->setParameter('idFormularioPorCentroEducativo',$formularioPorCentroEducativo)
                        ->getQuery()->getResult();
            if(count($seccSinRespuesta)==0){

                $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                    'codEstadoFormulario' => 'TE',
                )));
                new AccionPorUsuario($em,$this->getUser(),'TF',$formularioPorCentroEducativo);
                $em->persist($formularioPorCentroEducativo);
                $em->flush();

            }
            else{
                $seccStrArr=array();
                foreach ($seccSinRespuesta as $secc) {
                    $seccStrArr[]='<li>' . $secc['codSeccion'] . ' - ' . $secc['nbrSeccion'] . '</li>';
                }
                $msgArchivo='';
                if($secc['nbrArchivo']=='' || $secc['rutaArchivo']==''){
                    $msgArchivo='Debe cargar el archivo correspondiente al instrumento';
                }
                $session->getFlashBag()->add('error','Las siguientes secciones/criterios no tienen respuestas registradas: <ul>' . implode('', $seccStrArr) . '</ul>' . $msgArchivo);
            }
        }
        else{
            $session->getFlashBag()->add('error','El estado del formulario no es el correcto para terminarlo.');
        }
        $session->remove('idFormularioPorCentroEducativo');

        return $this->redirectToRoute('centro_educativo_form_dig_corr');
    }

    /**
     * @Security("has_role('ROLE_REVISOR')")
     */
    public function aprobarRechazarAction(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativoRevisar');
        if($request->get('Aprobar')!=''){
            $estadoFormulario='AP';
            $accionPorUsuario='AF';
        }
        elseif($request->get('Rechazar')!=''){
            $estadoFormulario='RE';
            $accionPorUsuario='RF';
        }

        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $codEstadoFormulario=$formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(in_array($codEstadoFormulario,array('TE'))){
            $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                'codEstadoFormulario' => $estadoFormulario,
            )));
            new AccionPorUsuario($em,$this->getUser(),$accionPorUsuario,$formularioPorCentroEducativo);
            $em->persist($formularioPorCentroEducativo);
            $em->flush();
        }
        else{
            $session->getFlashBag()->add('error','El estado del formulario no es el correcto para aprobarlo/rechazarlo.');
        }

        return $this->redirectToRoute('centro_educativo_form_lista_revisar');
    }
}
