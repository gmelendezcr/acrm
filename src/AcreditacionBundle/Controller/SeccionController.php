<?php

namespace AcreditacionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Seccion;
use AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo;
use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $seccions = $em->getRepository('AcreditacionBundle:Seccion')->findAll();

        return $this->render('seccion/index.html.twig', array(
            'seccions' => $seccions,
        ));
    }

    /**
     * Finds and displays a Seccion entity.
     *
     */
    public function showAction(Seccion $seccion)
    {
//este valor debe estar en sesión o venir como parte del formulario que se está llenando
$idFormularioPorCentroEducativo=3;
        $idSeccion=$seccion->getIdSeccion();
        $em = $this->getDoctrine()->getManager();
        $resps=$em->createQueryBuilder()
            ->select('p.idPregunta, t.codTipoPregunta, pp.idPregunta as idPreguntaPadre, ore.idOpcionRespuesta, rfc.valorRespuesta')
            ->from('AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo', 'rfc')
            ->join('rfc.idPregunta','p')
            ->join('p.idSeccion','s')
            ->join('p.idTipoPregunta','t')
            ->leftJoin('rfc.idOpcionRespuesta','ore')
            ->leftJoin('p.idPreguntaPadre','pp')
            ->where('rfc.idFormularioPorCentroEducativo=:idFormularioPorCentroEducativo')
            ->andWhere('s.idSeccion=:idSeccion')
                ->setParameter('idFormularioPorCentroEducativo',$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo))
                ->setParameter('idSeccion',$em->getRepository('AcreditacionBundle:Seccion')->find($idSeccion))
                    ->getQuery()->getResult();
        $respuestas=array();
        foreach($resps as $resp){
            if($resp['idPreguntaPadre'] && $resp['idOpcionRespuesta']){
                $respuestas[$resp['idPregunta']][$resp['idOpcionRespuesta']]=$resp['valorRespuesta'];
            }
            else{
                $respuestas[$resp['idPregunta']]=$resp['valorRespuesta'];
            }
        }
//var_dump($respuestas);

        return $this->render('seccion/showAsForm.html.twig', array(
            'seccion' => $seccion,
            'respuestas' => $respuestas,
        ));
    }

    /**
     * Guarda las preguntas de una sección
     *
     */
    public function guardarAction(Request $request)
    {
//este valor debe estar en sesión o venir como parte del formulario que se está llenando
$idFormularioPorCentroEducativo=3;
        $idSeccion=$request->get('idSeccion');
        $em = $this->getDoctrine()->getManager();
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
                if(!$value){
                    continue;
                }
                $resp=new RespuestaPorFormularioPorCentroEducativo();
                $resp->setIdFormularioPorCentroEducativo($em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativo));
                $resp->setIdPregunta($em->getRepository('AcreditacionBundle:Pregunta')->find($idPregunta));
                if($idOpcionRespuesta){
                    $resp->setIdOpcionRespuesta($em->getRepository('AcreditacionBundle:OpcionRespuesta')->find($idOpcionRespuesta));
                }
            }
            else{
                if(!$value){
                    $em->remove($resp);
                    continue;
                }
            }
            $resp->setValorRespuesta($value);
            $em->persist($resp);
        }
        $em->flush();

        return $this->redirectToRoute('seccion_show', array('id' => $idSeccion));
    }
}
