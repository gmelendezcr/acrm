<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Departamento controller.
 *
 */
class DepartamentoController extends Controller
{
    public function comboDepartamentoAction(Request $request)
    {
        $idDepartamento=$request->get('idDepartamento');
        $idMunicipio=$request->get('idMunicipio');

        $em = $this->getDoctrine()->getManager();
        $deps=$em->createQueryBuilder()
            ->select('d.idDepartamento, d.codDepartamento, d.nbrDepartamento')
            ->from('AcreditacionBundle:Departamento', 'd')
                ->getQuery()->getResult();
        $depHtml='<option value="">Seleccione uno</option>';
        foreach($deps as $dep) {
            $depHtml.="<option value='" . $dep['idDepartamento'] . "'>" . $dep['codDepartamento'] . ' - ' . $dep['nbrDepartamento'] . "</option>";
        }

        return new Response($depHtml);
    }
}
