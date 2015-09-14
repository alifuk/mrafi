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
     * @Route("/profil/{id}", name="main_profil" )
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function profileAction($id) {
        $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($id);

        return ['user' => $user];
    }

    /**
     * @Route("/settings", name="main_settings")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function settingsAction(Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($user->getId());

        dump($request);

        if ($request->isMethod('POST')) {
            dump("jop");
            $name = $request->request->get('name');
            $adress1 = $request->request->get('adress1');
            $adress2 = $request->request->get('adress2');
            $phone = $request->request->get('phone');
            $dic = $request->request->get('dic');

            $user->setName($name);
            $user->setAdress1($adress1);
            $user->setAdress2($adress2);
            $user->setPhone($phone);
            $user->setDic($dic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return ['user' => $user];
    }

    /**
     * @Route("/demand", name="main_demand")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function demandAction(Request $request) {

        $myDemands = $this->getDoctrine()
                ->getRepository('AppBundle:Items')
                ->getMyDemands();

        return ['user' => $user];
    }

    /**
     * @Route("/offer", name="main_offer")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function offerAction(Request $request) {

        return [];
    }

}
