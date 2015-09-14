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

class MainController extends Controller {

    /**
     * @Route("/", name="main_landingpage")
     * @Template()
     */
    public function landingPageAction() {

        
        $task = new User();
        $task->setPhone("606 544 258");

        $formRegister = $this->createFormBuilder($task, ['action' => $this->generateUrl('user_register')])
                ->add('email', 'text')
                ->add('password', 'text')
                ->add('ico', 'text')
                ->add('register', 'submit', array('label' => 'Zaregistrovat'))
                ->getForm();

        $formLogin = $this->createFormBuilder($task, ['action' => $this->generateUrl('security_login_check')])
                ->add('email', 'text')
                ->add('password', 'text')
                ->add('login', 'submit', array('label' => 'Přihlásit'))
                ->getForm();

        return [
            'form_register' => $formRegister->createView(),
            'form_login' => $formLogin->createView()
        ];
    }


        
    
    /**
     * @Route("/profile", name="main_profile")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function profileAction() {
        return [];
    }
    

    /**
     * @Route("/settings", name="main_settings")
     * @Template()
     */
    public function nastaveniAction() {
        return [];
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
