<?php
namespace AcreditacionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AcreditacionBundle\Entity\Departamento;
use AcreditacionBundle\Entity\Municipio;
use AcreditacionBundle\Entity\CentroEducativo;
use AcreditacionBundle\Entity\AccionPorUsuario;
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
                'csrf_token' => $csrfToken,
            ));
        }
    }

    public function postLoginAction()
    {
        $user=$this->getUser();
        if(substr($_SERVER['HTTP_REFERER'],-6)=='/admin' && $user){
            $em=$this->getDoctrine()->getManager();
            new AccionPorUsuario($em,$user,'IN',$user);
            $em->flush();
        }
        return $this->redirectToRoute('acreditacion_homepage');
    }

    public function preLogoutAction()
    {
        $user=$this->getUser();
        if($user){
            $em=$this->getDoctrine()->getManager();
            new AccionPorUsuario($em,$user,'SA',$user);
            $em->flush();
        }
        return $this->redirectToRoute('fos_user_security_logout');
    }

    public function inicioAction(){
        
        
        /*
    ----------------------------------------------------------------------------
    -> Portada sistema
    ----------------------------------------------------------------------------
    */
  
        $em = $this->getDoctrine()->getManager();
        $queryCentros=$em->createQueryBuilder()
        ->select('fce.idFormularioPorCentroEducativo, c.codCentroEducativo, c.nbrCentroEducativo, f.codFormulario')
        ->from('AcreditacionBundle:FormularioPorCentroEducativo', 'fce')
            ->join('fce.idCentroEducativo','c')
            ->join('fce.idFormulario','f')
            ->join('fce.idEstadoFormulario','e')
            ->join('fce.idUsuarioDigita','u')
                ->orderBy('e.codEstadoFormulario','desc')
                ->setMaxResults('5');

        $queryCentros1=clone $queryCentros;
        $queryCentros1
            ->where('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->setParameter('codEstadoFormulario',array('DI','CO'));
        $lista1=$queryCentros1
                ->getQuery()->getArrayResult();
        $num_form1=count($lista1);

        $queryCentros2=clone $queryCentros;
        $queryCentros2
            ->where('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->setParameter('codEstadoFormulario',array('TE'));
        $lista2=$queryCentros2
                ->getQuery()->getArrayResult();
        $num_form2=count($lista2);

        $queryCentros3=clone $queryCentros;
        $queryCentros3
            ->where('e.codEstadoFormulario in (:codEstadoFormulario)')
            ->setParameter('codEstadoFormulario',array('AP'));
        $lista3=$queryCentros3
                ->getQuery()->getArrayResult();
        $num_form3=count($lista3);

        return $this->render('default/inicio.index.html.twig',array(
            'lista1' => $lista1,
            'num_form1' => $num_form1,
            'lista2' => $lista2,
            'num_form2' => $num_form2,
            'lista3' => $lista3,
            'num_form3' => $num_form3,
        ));
    }
}
