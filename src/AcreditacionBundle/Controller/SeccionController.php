<?php

namespace AcreditacionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Seccion;

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

        return $this->render('seccion/show.html.twig', array(
            'seccion' => $seccion,
        ));
    }
}
