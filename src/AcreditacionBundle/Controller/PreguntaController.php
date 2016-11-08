<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Pregunta;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Pregunta controller.
 *
 */
class PreguntaController extends Controller
{
    /**
     * Lists all Pregunta entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $preguntas = $em->getRepository('AcreditacionBundle:Pregunta')->findAll();

        return $this->render('pregunta/index.html.twig', array(
            'preguntas' => $preguntas,
        ));
    }

    /**
     * Finds and displays a Pregunta entity.
     *
     * @Security("has_role('ROLE_DIGITADOR') or has_role('ROLE_REVISOR') or has_role('ROLE_COORDINADOR')")
     */
    public function showAction(Pregunta $pregunta)
    {

        return $this->render('pregunta/showAsForm.html.twig', array(
            'pregunta' => $pregunta,
        ));
    }
}
