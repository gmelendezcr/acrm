<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Seccion;
use AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Seccion controller.
 *
 */
class SeccionController extends Controller
{
    /**
     * Lists all Seccion entities.
     *
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
        $estadoFormularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo)
            ->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(!$idFormularioPorCentroEducativo){
            return $this->redirectToRoute('homepage');
        }

        $resFormulario=$em->createQueryBuilder()
            ->select('f.idFormulario, f.nbrFormulario, c.codCentroEducativo, c.nbrCentroEducativo')
            ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idFormulario','f')
            ->join('fce.idCentroEducativo','c')
            ->where('fce.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
                ->setParameter('idFormularioPorCentroEducativo',$idFormularioPorCentroEducativo)
                    ->getQuery()->getSingleResult();
        $idFormulario=$em->getRepository('AcreditacionBundle:Formulario')->find($resFormulario['idFormulario']);

        $session->set('codCentroEducativo', $resFormulario['codCentroEducativo']);
        $session->set('nbrCentroEducativo', $resFormulario['nbrCentroEducativo']);
        $session->set('nbrFormulario', $resFormulario['nbrFormulario']);

        $seccions = $em->getRepository('AcreditacionBundle:Seccion')->findBy(array(
            'idFormulario' => $idFormulario,
        ));

        return $this->render('seccion/index.html.twig', array(
            'seccions' => $seccions,
            'idFormularioPorCentroEducativoRevisar' => $idFormularioPorCentroEducativoRevisar,
            'estadoFormularioPorCentroEducativo' => $estadoFormularioPorCentroEducativo
        ));
    }

    /**
     * Finds and displays a Seccion entity.
     *
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
            ->select('p.idPregunta, t.codTipoPregunta, pp.idPregunta as idPreguntaPadre, ore.idOpcionRespuesta, rfc.revisar, rfc.valorRespuesta, rfc.ponderacionGanada')
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
        $respuestas=$revisar=$puntuaciones=array();
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
        }

        return $this->render('seccion/showAsForm.html.twig', array(
            'seccion' => $seccion,
            'respuestas' => $respuestas,
            'revisar' => $revisar,
            'puntuaciones' => $puntuaciones,
            'idFormularioPorCentroEducativoRevisar' => $idFormularioPorCentroEducativoRevisar,
            'estadoFormularioPorCentroEducativo' => $formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario()
        ));
    }

    /**
     * Guarda las preguntas de una sección
     *
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
            foreach($request->request->all() as $key => $value){
                $matches=array();
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
                else{
                    error_log(__CLASS__ . '->' . __METHOD__ . ' (línea ' . __LINE__ . '), no se guardó: ' . "key: $key, value: $value");
                    continue;
                }
                $resp=$em->getRepository('AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo')->findOneBy(array(
                    'idFormularioPorCentroEducativo' => $idFormularioPorCentroEducativo,
                    'idPregunta' => $idPregunta,
                    'idOpcionRespuesta' => ($idOpcionRespuesta?$idOpcionRespuesta:null),
                ));
                if(!is_object($resp)){
                    if($value . ''===''){
                        continue;
                    }
                    $resp=new RespuestaPorFormularioPorCentroEducativo();
                    $resp->setIdFormularioPorCentroEducativo($formularioPorCentroEducativo);
                    $resp->setIdPregunta($em->getRepository('AcreditacionBundle:Pregunta')->find($idPregunta));
                    if($idOpcionRespuesta){
                        $resp->setIdOpcionRespuesta($em->getRepository('AcreditacionBundle:OpcionRespuesta')->find($idOpcionRespuesta));
                    }
                }
                else{
                    if($value.''===''){
                        $em->remove($resp);
                        continue;
                    }
                }
                $resp->setValorRespuesta($value);
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

    public function terminarAction(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$session->get('idFormularioPorCentroEducativo');
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $codEstadoFormulario=$formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(in_array($codEstadoFormulario,array('DI','CO'))){
            $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                'codEstadoFormulario' => 'TE',
            )));
            $em->persist($formularioPorCentroEducativo);
            $em->flush();
        }
        else{
            $session->getFlashBag()->add('error','El estado del formulario no es el correcto para terminarlo.');
        }
        $session->remove('idFormularioPorCentroEducativo');

        return $this->redirectToRoute('centro_educativo_form_dig_corr');
    }

    public function aprobarRechazarAction(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativoRevisar');
        if($request->get('Aprobar')!=''){
            $estadoFormulario='AP';
        }
        elseif($request->get('Rechazar')!=''){
            $estadoFormulario='RE';
        }

        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo);
        $codEstadoFormulario=$formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario();
        if(in_array($codEstadoFormulario,array('TE'))){
            $formularioPorCentroEducativo->setIdEstadoFormulario($em->getRepository('AcreditacionBundle:EstadoFormulario')->findOneBy(array(
                'codEstadoFormulario' => $estadoFormulario,
            )));
            $em->persist($formularioPorCentroEducativo);
            $em->flush();
        }
        else{
            $session->getFlashBag()->add('error','El estado del formulario no es el correcto para aprobarlo/rechazarlo.');
        }

        return $this->redirectToRoute('centro_educativo_form_lista_revisar');
    }
}
