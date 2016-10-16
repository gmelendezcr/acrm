<?php

namespace AcreditacionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AcreditacionBundle\Entity\Usuario;
use AcreditacionBundle\Form\UsuarioType;

/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{
    /**
     * Lists all Usuario entities.
     *
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
    
      public function editAction(Request $request, Usuario $usuario){
        //$rl=array('roles' => $this->container->getParameter('security.role_hierarchy.roles'));
        //var_dump($a);
        //--$em = $this->getDoctrine()->getManager();
        //--$edit_user = $em->getRepository('AcreditacionBundle:Usuario')->find($usuario);
      
        
        
        
        //var_dump($edit_user);
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('AcreditacionBundle\Form\UsuarioType', $usuario);
        $editForm->handleRequest($request);
        //$userManager = $this->get('fos_user.user_manager');
        //$user = $userManager->findUserBy(['id' => 1]);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {
        //$form = $this->createDeleteForm($usuario);
        //$form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
}
