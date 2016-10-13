<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Municipio controller.
 *
 */
class MunicipioController extends Controller
{
    public function comboMunicipioAction(Request $request)
    {
        $idDepartamento=$request->get('idDepartamento');
        $idMunicipio=$request->get('idMunicipio');

        $em = $this->getDoctrine()->getManager();
        $muns=$em->createQueryBuilder()
            ->select('m.idMunicipio, m.codMunicipio, m.nbrMunicipio')
            ->from('AcreditacionBundle:Municipio', 'm')
            ->join('m.idDepartamento','d')
            ->where('d.idDepartamento=:idDepartamento')
                ->setParameter('idDepartamento',$idDepartamento)
                    ->getQuery()->getResult();
        $munHtml='<option value="">Seleccione uno</option>';
        foreach($muns as $mun) {
            $munHtml.="<option value='" . $mun['idMunicipio'] . "' " . ($mun['codMunicipio']==$idMunicipio?'selected':'') . ">" . $mun['codMunicipio'] . ' ' . $mun['nbrMunicipio'] . "</option>";
        }

        return new Response($munHtml);
    }
}
