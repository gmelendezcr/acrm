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
        return $this->render('default/inicio.index.html.twig');
    }
}
