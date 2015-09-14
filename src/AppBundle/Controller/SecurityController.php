<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller {

    /**
     * @Route("/registerForm", name="user_registerForm")
     * @Template()
     */
    public function registerFormAction() {
        return [];
    }

    /**
     * @Route("/register", name="user_register")
     * @Method("POST")
     */
    public function registerAction(Request $request) {
        $em = $this->getDoctrine()->getManager();


        $request = Request::createFromGlobals();
        $email = $request->request->get('email');
        
        $password = $request->request->get('password');

        $ico = $request->request->get('ico');

        $user = new User();

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $user->setEmail($email);
        $user->setIco($ico);
        $backgroundImages = ['animal', 'corn', 'farming', 'chicken'];
        shuffle($backgroundImages);
        $user->setBackgroundImage($backgroundImages[0]);


        $profileImages = ['animal', 'corn', 'farming', 'chicken'];
        shuffle($profileImages);
        $user->setProfileImage($profileImages[0]);

        $em->persist($user);
        $em->flush();

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);


        return $this->redirect($this->generateUrl('main_landingpage'));
    }

    /**
     * @Route("/login", name="security_loginForm")
     */
    public function loginFormAction() {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        dump($error);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        'AppBundle::Security/loginForm.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction() {
        throw new Exception('This should never be reached!');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction() {
        throw new Exception('This should never be reached!');
    }

}
