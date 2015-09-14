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

class SecurityController extends Controller {

    /**
     * @Route("/register", name="user_register")
     * @Method("POST")
     * @Template()
     */
    public function registerAction(Request $request) {

        $user = new User();
        $formRegister = $this->createFormBuilder($user, ['action' => $this->generateUrl('user_register'),])
                ->add('email', 'text')
                ->add('password', 'text')
                ->add('ico', 'text')
                ->add('register', 'submit', array('label' => 'Zaregistrovat'))
                ->getForm();
        $formRegister->handleRequest($request);

        if ($formRegister->isValid()) {
            $plainPassword = $user->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encoded);


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('main_landingpage'));
    }
    
    
    /**
     * @Route("/login", name="security_loginForm")
     */
    public function loginFormAction()
    {   
        $helper = $this->get('security.authentication_utils');

        return $this->render('AppBundle::Security/loginForm.html.twig', array(
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ));
    }
    
    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        throw new Exception('This should never be reached!');
    }

    /** 
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new Exception('This should never be reached!');
    }
    
    
    
    
    
    
    
    
    

}
