<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Conversation;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
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
        $message = new Message();
        $messages = new \Doctrine\Common\Collections\ArrayCollection();
        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $conversations = $this->getUser()->getConversations();

        //fetch all messages from conversations
        foreach($conversations as $conversation){
            $messages[$conversation->getId()] = $conversation->getMessages();
        }

        //AJAX call
        if($request->isXmlHttpRequest()){
            $id = $request->query->get('conversationId');
            $content =  $this->renderView('conversation/show.html.twig', array(
                'messages' => $messages[$id],
                'id' => $id,
                'form' => $form->createView(),
            ));

            $jsonResponse = new JsonResponse();
            $jsonResponse->setData(array('content' => $content));
            return $jsonResponse;
        }

        if(null !== $request->query->get('conversationId')){
            $id = $request->query->get('conversationId');

            return $this->render('conversation/index.html.twig', array(
                'messages' => $messages[$request->query->get('conversationId')],
                'id' => $id,
                'conversations' => $conversations,
                'form' => $form->createView(),
            ));
        } else {
            return $this->render('conversation/index.html.twig', array(
                'messages' => null,
                'id' => null,
                'conversations' => $conversations,
                'form' => $form->createView(),
            ));
        }

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
         $user = new User();
         $users = new \Doctrine\Common\Collections\ArrayCollection();
         $form = $this->createForm('AppBundle\Form\MessageType', $message);
         $form->handleRequest($request);
         $date = date('H:i:s d-m-Y');
         $author = $this->getUser()->getName();
         $userLogged = $this->getUser();

         if ($form->isSubmitted() && $form->isValid()) {
             $conversationId = $request->query->get('conversationId');
             $conversation = $this->getDoctrine()->getRepository('AppBundle:Conversation')->find($conversationId);
             $users = $conversation->getUsers();

             foreach($users as $u){
                 if($u !== $this->getUser()){
                     $user = $u;
                 }
             }

             if($userLogged->getNewmsg()){
                 $userLogged->setNewmsg(false);
             }

             if(!$user->getNewmsg()){
                 $user->setNewmsg(true);
             }

             $message->setDate(new \DateTime($date));
             $message->setConversation($conversation);
             $message->setAuthor($author);

             $em = $this->getDoctrine()->getManager();
             $em->persist($message);
             $em->persist($conversation);
             $em->persist($userLogged);
             $em->persist($user);
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
        $senderId = $request->query->get('senderId');
        $receiverId = $request->query->get('receiverId');
        $productId = $request->query->get('productId');
        $date = date('H:i:s d-m-Y');

        $conversation = new Conversation();
        $message = new Message();

        $senderUser = $this->getDoctrine()->getRepository('AppBundle:User')->find($senderId);
        $receiverUser = $this->getDoctrine()->getRepository('AppBundle:User')->find($receiverId);
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($productId);
        $productName = $product->getName();

        $conversation->addUser($senderUser);
        $conversation->addUser($receiverUser);
        $conversation->setTitle("User: ".$senderUser->getName()." interested in your product: ".$productName );

        $message->setDate(new \DateTime($date));
        $message->setConversation($conversation);
        $message->setAuthor('Brafin System');
        $message->setText("Hello! The user: ".$senderUser->getName()." is interested in your product: ".$productName.".
                                 We've send this message to facilitate your negociations. Bra trades!");

        $em = $this->getDoctrine()->getManager();
        $em->persist($conversation);
        $em->persist($message);
        $em->flush();

        $senderUser->addConversation($conversation);
        $receiverUser->addConversation($conversation);

        $em->persist($senderUser);
        $em->persist($receiverUser);
        $em->flush();

        return $this->redirectToRoute('conversation_index');
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
