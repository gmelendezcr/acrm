<?php
namespace AcreditacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AcreditacionBundle\Entity\Departamento;
use AcreditacionBundle\Entity\Municipio;
use AcreditacionBundle\Entity\CentroEducativo;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller{
    public function indexAction(){
        return $this->render('AcreditacionBundle:Default:index.html.twig');
    }
    public function recoveryAction(){
        return $this->render('default/recovery.index.html.twig');
    }
    public function adminAction(){
        $user = $this->getUser();
        if (is_object($user)) {
            return $this->render('default/inicio.index.html.twig');
        }else{
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
            return $this->render('default/admin.index.html.twig',array(
//            'last_username' => $lastUsername,
//            'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }
    }
    public function inicioAction(){
        
        
        /*
    ----------------------------------------------------------------------------
    -> Portada sistema
    ----------------------------------------------------------------------------
    */
  
        $em = $this->getDoctrine()->getEntityManager();
        $lista=$em->createQueryBuilder()
        ->select('fce.idFormularioPorCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, c.direccionCentroEducativo,
        f.codFormulario, f.nbrFormulario, u.nombres, u.apellidos, e.codEstadoFormulario, e.nbrEstadoFormulario')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
                ->andWhere('e.codEstadoFormulario in (:codEstadoFormulario)')
                ->setParameter('codEstadoFormulario',array('AP'))
                ->orderBy('e.codEstadoFormulario','desc')
                ->setMaxResults('5')
                ->getQuery()->getArrayResult();
                $num_form=count($lista);
                return $this->render('default/inicio.index.html.twig',array(
                'lista'=>$lista,
                'num_form'=>$num_form
        ));
    }
}
