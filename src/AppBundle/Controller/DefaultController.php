<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')->findAll();
        $categories = $em->getRepository('AppBundle:Category')->findAll();        
        
        //filtering by categories
        if(null !== $request->request->get('categoryId') && "" != $request->request->get('categoryId')){
            $categoryId = $request->request->get('categoryId');
            $category = $em->getRepository('AppBundle:Category')->find($categoryId);
            $products = $category->getProducts();
        }
        
        //filtering by search
        if(null !== $request->query->get('search') && "" != $request->query->get('search')){
            $search = $request->query->get('search');
            
            foreach ($products as $key => $product) {
                if(false === strpos(strtolower($product->getName()), strtolower($search))){
                    unset($products[$key]);
                }
            }
        }
        
        if($request->isXmlHttpRequest()) {
            $content =  $this->renderView('default/content.html.twig', array(
                'products' => $products,
            ));
            $jsonResponse = new JsonResponse();
            $jsonResponse->setData(array('content' => $content));            
            return $jsonResponse;
            
        } else {
            
            return $this->render('default/index.html.twig', array(
                'products' => $products,
                'categories' => $categories,
            ));
        }
    }
}