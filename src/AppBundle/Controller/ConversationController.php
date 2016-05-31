<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Conversation;
use AppBundle\Entity\Message;
use AppBundle\Form\ConversationType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Conversation controller.
 *
 * @Route("/conversation")
 */
class ConversationController extends Controller
{
    /**
     * Lists all Conversation entities.
     *
     * @Route("/", name="conversation_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $messages = new \Doctrine\Common\Collections\ArrayCollection();
        $message = new Message();
        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $conversations = $this->getUser()->getConversations();

        //fetch all messages from conversations
        foreach($conversations as $conversation){
            $messages[$conversation->getId()] = $conversation->getMessages();
        }

        //AJAX call
        if($request->isXmlHttpRequest()){
            $id = $request->request->get('conversationId');
            $content =  $this->renderView('conversation/show.html.twig', array(
                'messages' => $messages[$id],
                'form' => $form->createView(),
                'id' => $id,
            ));

            $jsonResponse = new JsonResponse();
            $jsonResponse->setData(array('content' => $content));
            return $jsonResponse;
        }

        return $this->render('conversation/index.html.twig', array(
            'conversations' => $conversations,
        ));
    }

    /**
     * Creates a new Message entity.
     *
     * @Route("/new_message", name="conversation_msg_new")
     * @Method({"GET", "POST"})
     */

     public function newMsgAction(Request $request)
     {
         $message = new Message();
         $form = $this->createForm('AppBundle\Form\MessageType', $message);
         $form->handleRequest($request);
         $conversationId = $request->query->get('conversation-id');
         //New Message
         if ($form->isSubmitted() && $form->isValid()) {
             $conversation = $this->getDoctrine()->getRepository('AppBundle:Conversation')->find($conversationId);
             $author = $this->getUser()->getName();
             $date = date('H:i:s d-m-Y');

             $message->setViewed(false);
             $message->setDate(new \DateTime($date));
             $message->setConversation($conversation);
             $message->setAuthor($author);

             $em = $this->getDoctrine()->getManager();
             $em->persist($message);
             $em->flush();

             return $this->redirectToRoute('conversation_index', array('conversationId' => $conversationId));
         }
     }

    /**
     * Creates a new Conversation entity.
     *
     * @Route("/new", name="conversation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $conversation = new Conversation();
        $form = $this->createForm('AppBundle\Form\ConversationType', $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);
            $em->flush();

            return $this->redirectToRoute('conversation_show', array('id' => $conversation->getId()));
        }

        return $this->render('conversation/new.html.twig', array(
            'conversation' => $conversation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Conversation entity.
     *
     * @Route("/{id}", name="conversation_show")
     * @Method("GET")
     */
    public function showAction(Conversation $conversation)
    {
        $deleteForm = $this->createDeleteForm($conversation);

        return $this->render('conversation/show.html.twig', array(
            'conversation' => $conversation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Conversation entity.
     *
     * @Route("/{id}/edit", name="conversation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Conversation $conversation)
    {
        $deleteForm = $this->createDeleteForm($conversation);
        $editForm = $this->createForm('AppBundle\Form\ConversationType', $conversation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);
            $em->flush();

            return $this->redirectToRoute('conversation_edit', array('id' => $conversation->getId()));
        }

        return $this->render('conversation/edit.html.twig', array(
            'conversation' => $conversation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Conversation entity.
     *
     * @Route("/{id}", name="conversation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Conversation $conversation)
    {
        $form = $this->createDeleteForm($conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($conversation);
            $em->flush();
        }

        return $this->redirectToRoute('conversation_index');
    }

    /**
     * Creates a form to delete a Conversation entity.
     *
     * @param Conversation $conversation The Conversation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Conversation $conversation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conversation_delete', array('id' => $conversation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
