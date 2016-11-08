<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\FormularioPorCentroEducativo;
use AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo;
use AcreditacionBundle\Entity\AccionPorUsuario;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\Query\ResultSetMapping;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * FormularioPorCentroEducativo controller.
 *
 */
class FormularioPorCentroEducativoController extends Controller
{
    /**
     * Lists all FormularioPorCentroEducativo entities.
     *
     * @Security("has_role('ROLE_REVISOR')")
     */
    public function respuestaRevisarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idFormularioPorCentroEducativoRevisar=$request->get('idFormularioPorCentroEducativoRevisar');
        $idPregunta=$request->get('idPregunta');
        $idOpcionRespuesta=$request->get('idOpcionRespuesta');

        $nuevoRevisar='';
        $formularioPorCentroEducativo=$em->getRepository('AcreditacionBundle:FormularioPorCentroEducativo')->find($idFormularioPorCentroEducativoRevisar);
        if($formularioPorCentroEducativo->getIdEstadoFormulario()->getCodEstadoFormulario()=='TE'){

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
                $respuestaPorFormularioPorCentroEducativo->setIdFormularioPorCentroEducativo($formularioPorCentroEducativo);
                $respuestaPorFormularioPorCentroEducativo->setIdPregunta(
                    $em->getRepository('AcreditacionBundle:Pregunta')->find($idPregunta));
                if($idOpcionRespuesta){
                    $respuestaPorFormularioPorCentroEducativo->setIdOpcionRespuesta(
                        $em->getRepository('AcreditacionBundle:OpcionRespuesta')->find($idOpcionRespuesta));
                }
                $respuestaPorFormularioPorCentroEducativo->setRevisar($nuevoRevisar);
            }
            new AccionPorUsuario($em,$this->getUser(),'MF',$respuestaPorFormularioPorCentroEducativo);
            $em->persist($respuestaPorFormularioPorCentroEducativo);
            $em->flush();

        }

        return new Response($nuevoRevisar);
    }

    /**
     * @Security("has_role('ROLE_COORDINADOR')")
     */
    public function calificarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idFormularioPorCentroEducativo=$request->get('idFormularioPorCentroEducativo');

        new AccionPorUsuario($em,$this->getUser(),'CF');
        $em->flush();
        $em->getConnection()
            ->prepare("CALL CALIFICAR_FORMULARIO ($idFormularioPorCentroEducativo)")
                ->execute();

        return $this->redirectToRoute('centro_educativo_form_lista_evaluar');
    }
}
