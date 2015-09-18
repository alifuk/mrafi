<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository;

class MainController extends Controller {

    /**
     * @Route("/", name="main_landingpage")
     * @Template()
     */
    public function landingPageAction() {
        $task = new User();
        $task->setPhone("606 544 258");

        $formRegister = $this->createFormBuilder($task, ['action' => $this->generateUrl('security_register')])
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
    public function demandAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $myDemands = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findOwnedBy($user);

        return ['demands' => $myDemands];
    }

    /**
     * @Route("/demand-create", name="main_demandCreate")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function demandCreateAction(Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->findAll();


        if ($request->isMethod('POST')) {
            dump("ukladani");

            $name = $request->request->get('name');
            $public = $request->request->get('public');
            $note = $request->request->get('note');
            $category = $request->request->get('category');

            if (null === $public) {
                $public = false;
            } else {
                $public = true;
            }
            dump($request);


            $categoryObj = $this->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->find($category);


            $item = new Item();
            $item->setName($name);
            $item->setOwner($user);
            $item->setType(Item::TYPE_DEMAND);
            $item->setPublic($public);
            $item->setCategory($categoryObj);
            $item->setNote($note);
            $item->setDeleted(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            
            
            $this->addFlash('success', 'Poptávka úspěšně vytvořena');
            return $this->redirect($this->generateUrl('main_demand'));
        }

        return ['categories' => $categories];
    }

    /**
     * @Route("/offer", name="main_offer")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function offerAction() {

        return [];
    }

    /**
     * @Route("/overview", name="main_overview")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function overviewAction() {

        return [];
    }

    /**
     * @Route("/menuLiCategories", name="main_menuLiCategories")
     * @Template()
     */
    public function menuLiCategoriesAction() {
        $categories = $this->getDoctrine()
                ->getRepository("AppBundle:Category")
                ->findAll();

        return ['categories' => $categories];
    }

    /**
     * @Route("/category/{categoryUrl}", name="main_category")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function categoryAction($categoryUrl) {

        $category = $this->getDoctrine()
                ->getRepository("AppBundle:Category")
                ->findOneBy(['urlName' => $categoryUrl]);
        
        if (null === $category) {
            $this->addFlash('error', 'Špatná kategorie!');
            return $this->redirect($this->generateUrl('main_landingpage'));
        }
        return ['category' => $category];
    }

}
