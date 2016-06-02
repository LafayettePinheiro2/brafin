<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Image;
use AppBundle\Form\ProductType;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * Lists all Product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $user = new User();

        $img = new Image();
        $product->getImages()->add($img);
        $user = $this->getUser();
        $availableCredit = $user->getCredit();

        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setUser($this->getUser());

            $images = $product->getImages();
            $count = count($images);
            $img = $images[$count-1];
            $img->setProduct($product);
            $user->setCredit($availableCredit+1);
            $product->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Donates a Product entity.
     *
     * @Route("/donate/", name="donate_product")
     * @Method("GET")
     */
    public function donateProduct(Request $request)
    {
        $productId = $request->query->get('productId');
        $userId = $request->query->get('interestedUser');

        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($productId);
        $user = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->find($userId);
        $conversations = $product->getConversation();
        $credits = $user->getCredit();

        $product->setAvailable(false);
        $user->setCredit($credits-1);
        $user->setNewmsg(false);

        $em = $this->getDoctrine()->getManager();

        foreach($conversations as $conversation){
            $product->removeConversation($conversation);

            $em->remove($conversation);
            $em->flush();
        }

        $em->persist($product);
        $em->persist($user);

        $em->flush();

        return $this->redirectToRoute('user_show', array('id' => $this->getUser()->getId()));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        $image = new Image();
        $imageForm = $this->createForm('AppBundle\Form\ImageType', $image);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->setProduct($product);
            $em->persist($product);
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'image_form' => $imageForm->createView(),
        ));
    }


    /**
     * Remove an image from the product
     *
     * @Route("/{id}/remove-image", name="product_delete_image")
     * @Method({"GET", "POST", "DELETE"})
     */
    public function removeImageAction(Request $request, Product $product)
    {
        $imageId  = $request->query->get('image-id');

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($imageId);

        $product->removeImage($image);
        $em->persist($product);
        $em->remove($image);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Image removed with success')
        ;

        return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
    }


    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a Product entity.
     *
     * @param Product $product The Product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
