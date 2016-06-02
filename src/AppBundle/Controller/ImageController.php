<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * Lists all Image entities.
     *
     * @Route("/", name="image_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findAll();

        return $this->render('image/index.html.twig', array(
            'images' => $images,
        ));
    }

    /**
     * Creates a new Image entity.
     *
     * @Route("/new", name="image_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $form = $this->createForm('AppBundle\Form\ImageType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();            
            $em->persist($image);
            $em->flush();
            
            return $this->redirectToRoute('image_show', array('id' => $image->getId()));
        }

        return $this->render('image/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }
    
    
    /**
     * Creates a new Image entity for the product.
     *
     * @Route("/new-image-product", name="image_product_new")
     * @Method({"GET", "POST"})
     */
    public function newImageProductAction(Request $request)
    {       
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $form = $this->createForm('AppBundle\Form\ImageType', $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $productId = $request->query->get('product-id');
            $product = $em->getRepository('AppBundle:Product')->find($productId);
            $image->setProduct($product);
            
            $em = $this->getDoctrine()->getManager();            
            $em->persist($image);
            $em->flush();
            
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Image added to the product with success')
            ;
            
            return $this->redirectToRoute('product_edit', array('id' => $productId));
            
        }
    }
    
    /**
     * Creates a new Image entity for the product.
     *
     * @Route("/new-image-user", name="image_user_new")
     * @Method({"GET", "POST"})
     */
    public function newImageUserAction(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $form = $this->createForm('AppBundle\Form\ImageType', $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $userId = $request->query->get('user-id');
            $user = $em->getRepository('AppBundle:User')->find($userId);
            $image->setUser($user);
            
            $em = $this->getDoctrine()->getManager();            
            $em->persist($image);
            $em->flush();
            
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Image added to the user with success')
            ;
            
            return $this->redirectToRoute('user_edit', array('id' => $userId));
            
        }
    }
    

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="image_show")
     * @Method("GET")
     */
    public function showAction(Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('image/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit", name="image_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);
        $editForm = $this->createForm('AppBundle\Form\ImageType', $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('image_edit', array('id' => $image->getId()));
        }

        return $this->render('image/edit.html.twig', array(
            'image' => $image,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}", name="image_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Image $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            
            if(null !== $request->query->get('product-id')){
                $productId = $request->query->get('product-id');
                $product = $em->getRepository('AppBundle:Product')->find($productId);
                $product->removeImage($image);
            } 
            
            $em->remove($image);
            $em->flush();
        }

        if(null !== $productId){
            return $this->redirectToRoute('product_show', array('id' => $productId));
        }
        return $this->redirectToRoute('image_index');
    }

    /**
     * Creates a form to delete a Image entity.
     *
     * @param Image $image The Image entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('image_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
