<?php

namespace AcreditacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcreditacionBundle:Default:index.html.twig');
    }
    
    
    
    public function recoveryAction()
    {
        return $this->render('default/recovery.index.html.twig');
    }
    
    public function adminAction()
    {
        return $this->render('default/admin.index.html.twig');
    }
    
    public function inicioAction()
    {
        return $this->render('default/inicio.index.html.twig');
    }
    public function adduserAction()
    {
        return $this->render('default/adduser.index.html.twig');
    }
     public function listauserAction()
    {
        return $this->render('default/listauser.index.html.twig');
    }
    
}
