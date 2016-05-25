<?php
// src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Entity\Product;

class SecurityController extends Controller
{
  /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
      $authenticationUtils = $this->get('security.authentication_utils');

      // get the login error if there is one
      $error = $authenticationUtils->getLastAuthenticationError();

      if($error){
        $request->getSession()->getFlashBag()->add('error', 'Invalid Credentials, please try again.');
      }
      // last username entered by the user
      $lastUsername = $authenticationUtils->getLastUsername();

      return $this->render(
          'security/login.html.twig',
          array(
              // last username entered by the user
              'last_username' => $lastUsername,
              'error'         => $error,
          )
      );
    }
}
?>
