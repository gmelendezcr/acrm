<?php

namespace AcreditacionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Usuario;
use AcreditacionBundle\Entity\AccionPorUsuario;
use AcreditacionBundle\Form\UsuarioType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{
    /**
     * Lists all Usuario entities.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('AcreditacionBundle:Usuario')->findAll();

        return $this->render('usuario/index.html.twig', array(
            'usuarios' => $usuarios,
        ));
    }

    /**
     * Creates a new Usuario entity.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        
        $usuario = new Usuario();
        $form = $this->createForm('AcreditacionBundle\Form\UsuarioType', $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$request->get('usuario');
            $us=$request->get('usuario');
            //$usuario->addRole($us['roles']['0']);
            $roles=$request->get('roles');
            $usuario->addRole($roles);
            $usuario->setPlainPassword($usuario->getPassword());
            new AccionPorUsuario($em,$this->getUser(),'AU',$usuario);
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('usuario/new.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function showAction(Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);

        return $this->render('usuario/show.html.twig', array(
            'usuario' => $usuario,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
     public function getRole() {
    $role = $this->roles[0];
    return $role;
}

public function setRole($role) {
    $this->setRoles(array($role));
}
     
   /* public function editAction(Request $request, Usuario $usuario)
    {
       $request = $this->container->get('request');

    //$formEditUser = $this->createForm(new ChangeUserRoleType());
    $formEditUser = $this->createForm('AcreditacionBundle\Form\UsuarioType', $usuario);
    $formEditUser->handleRequest($request);
    if ($formEditUser->isValid()) {
        $em = $this->getDoctrine()->getManager();
      
         $roles = $formEditUser->get('roles')->getData();
         //$roles->addRole($roles['roles']['0']);
        
         
            
            $em->persist($roles);
            $em->flush();
         
      
    }
    return $this->render('usuario/edit.html.twig', array(
            'usuario' => $usuario,
            'form' => $formEditUser->createView(),
            
        ));
    
    }
    
    */
    
    /* copia editar usuario*/
    
    /**
     * Edits a Usuario entity.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, Usuario $usuario){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AcreditacionBundle:Usuario')->find($usuario);
        $password = $user->getPassword();
        
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('AcreditacionBundle\Form\UsuarioType', $usuario);
        //$usuario->remove('password');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pss = $editForm->get('password')->getData();
            if($pss!="abcd"){
                $usuario->setPlainPassword(''); 
                $usuario->setPlainPassword($usuario->getPassword());
            }else{
                $usuario->setPassword($password);
            }
            new AccionPorUsuario($em,$this->getUser(),'MU',$usuario);
            $em->persist($usuario);
            $em->flush();
            return $this->redirectToRoute('usuario_index');
           
        }

        return $this->render('usuario/edit.html.twig', array(
            'usuario' => $usuario,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    /**
     * Deletes a Usuario entity.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {
        //$form = $this->createDeleteForm($usuario);
        //$form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            new AccionPorUsuario($em,$this->getUser(),'EU',$usuario);
            $em->remove($usuario);
            $em->flush();
        //}

        return $this->redirectToRoute('usuario_index');
    }

    /**
     * Creates a form to delete a Usuario entity.
     *
     * @param Usuario $usuario The Usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function falloAction(Request $request, Usuario $usuario){
       
        
        
        
        return $this->render('defaults/admin.index.html.twig');
    }
    
}
