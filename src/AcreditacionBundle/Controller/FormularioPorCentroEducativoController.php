<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\FormularioPorCentroEducativo;
use AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * FormularioPorCentroEducativo controller.
 *
 */
class FormularioPorCentroEducativoController extends Controller
{
    /**
     * Lists all FormularioPorCentroEducativo entities.
     *
     */
    public function respuestaRevisarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idFormularioPorCentroEducativoRevisar=$request->get('idFormularioPorCentroEducativoRevisar');
        $idPregunta=$request->get('idPregunta');
        $idOpcionRespuesta=$request->get('idOpcionRespuesta');

        $respuestaPorFormularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:RespuestaPorFormularioPorCentroEducativo')->findOneBy(array(
            'idFormularioPorCentroEducativo' => $idFormularioPorCentroEducativoRevisar,
            'idPregunta' => $idPregunta,
            'idOpcionRespuesta' => ($idOpcionRespuesta?$idOpcionRespuesta:null),
        ));
        if($respuestaPorFormularioPorCentroEducativo){
            $revisar=$respuestaPorFormularioPorCentroEducativo->getRevisar();
            if($revisar=='S'){
                $nuevoRevisar='N';
            }
            else{
                $nuevoRevisar='S';
            }
            $respuestaPorFormularioPorCentroEducativo->setRevisar($nuevoRevisar);
        }
        else{
            $nuevoRevisar='S';
            $respuestaPorFormularioPorCentroEducativo=new respuestaPorFormularioPorCentroEducativo();
            $respuestaPorFormularioPorCentroEducativo->setIdFormularioPorCentroEducativo(
                $em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativoRevisar));
            $respuestaPorFormularioPorCentroEducativo->setIdPregunta(
                $em->getRepository('AcreditacionBundle:Pregunta')->find($idPregunta));
            if($idOpcionRespuesta){
                $respuestaPorFormularioPorCentroEducativo->setIdOpcionRespuesta(
                    $em->getRepository('AcreditacionBundle:OpcionRespuesta')->find($idOpcionRespuesta));
            }
            $respuestaPorFormularioPorCentroEducativo->setRevisar($nuevoRevisar);
        }
        $em->persist($respuestaPorFormularioPorCentroEducativo);
        $em->flush();

        return new Response($nuevoRevisar);
    }
}
