<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;
use AppBundle\Entity\Image;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Make Admin.
     *
     * @Route("/make-admin/{id}", name="make_admin")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
     public function makeAdmin(User $user){

       $user->setRoles('ROLE_ADMIN');

       $em = $this->getDoctrine()->getManager();
       $em->persist($user);
       $em->flush();

       return $this->redirectToRoute('user_index');
     }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        
        $img = new Image();
        $user->getImages()->add($img);

        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPlainPassword(null);
            
            $images = $user->getImages();
            $count = count($images);
            $img = $images[$count-1];
            $img->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $products = $user->getProducts();
        $hasNewMsg = $user->getNewmsg();

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'products' => $products,
            'delete_form' => $deleteForm->createView(),
            'hasNewMsg' => $hasNewMsg,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        $image = new Image();
        $imageForm = $this->createForm('AppBundle\Form\ImageType', $image);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPlainPassword(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),            
            'image_form' => $imageForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
    
    
    /**
     * Remove an image from the user
     *
     * @Route("/{id}/remove-image", name="user_delete_image")
     * @Method({"GET", "POST", "DELETE"})
     */
    public function removeImageAction(Request $request, User $user)
    {
        $imageId  = $request->query->get('image-id');

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($imageId);

        $user->removeImage($image);
        $em->persist($user);
        $em->remove($image);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Image removed with success')
        ;

        return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
    }
    

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
