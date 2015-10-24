<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Config;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Category;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Gathering;
use AppBundle\Entity\Item;
use AppBundle\Entity\User;
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

        $demands = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findDemandsOf($user);

        $offers = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findOffersOf($user);

        return ['user' => $user, 'demands' => $demands, 'offers' => $offers];
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
                ->findDemandsOf($user);

        $persOffers = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findPersonificatedOffersFor($user);


        return ['demands' => $myDemands, 'persOffers' => $persOffers];
    }

    /**
     * @Route("/offer", name="main_offer")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function offerAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $myOffers = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findOffersOf($user);

        $persDemands = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findPersonificatedDemandsFor($user);



        return ['offers' => $myOffers, 'persDemands' => $persDemands];
    }

    /**
     * @Route("/overview", name="main_overview")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function overviewAction() {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $persOffers = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findPersonificatedOffersFor($user);

        $persDemands = $this->getDoctrine()
                ->getRepository('AppBundle:Item')
                ->findPersonificatedDemandsFor($user);
        return ['offers' => $persOffers, 'demands' => $persDemands];
    }

    /**
     * @Route("/gatherings/", name="main_gatherings")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function gatheringsAction() {
        $gatherings = $this->getDoctrine()
                ->getRepository('AppBundle:Gathering')
                ->findAll();

        return ['gatherings' => $gatherings];
    }

    /**
     * @Route("/gathering/{gathering}", name="main_gathering")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function gatheringAction(Gathering $gathering) {

        return ['gathering' => $gathering];
    }

    /**
     * @Route("/addToGathering/{user}/{gathering}", name="main_addToGathering")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function addToGatheringAction(Gathering $gathering, User $user) {

        $em = $this->getDoctrine()->getManager();
        $user->addGathering($gathering);
        //$gathering->addUser($user);
        //$em->persist($gathering);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @Route("/removeFromGathering/{user}/{gathering}", name="main_removeFromGathering")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function removeFromGatheringAction(Gathering $gathering, User $user) {

        $em = $this->getDoctrine()->getManager();
        $user->removeGathering($gathering);
        //$gathering->addUser($user);
        //$em->persist($gathering);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @Route("/item/{item}", name="main_item")
     * @Template()
     */
    public function itemAction(Item $item) {
        $itemO = $this->getDoctrine()
                ->getRepository("AppBundle:Item")
                ->find($item);

        return ['item' => $itemO];
    }

    /**
     * @Route("/menuLiCategories", name="main_menuLiCategories")
     * @Template()
     */
    public function menuLiCategoriesAction() {

        $em = $this->getDoctrine()->getManager();
        $config = new Config($em);
        $nsm = new Manager($config);

        /* $categories = $this->getDoctrine()
          ->getRepository("AppBundle:Category")
          ->findBy(['parent' => null]); */
        $categories = array_merge($nsm->fetchTreeAsArray(13), $nsm->fetchTreeAsArray(15), $nsm->fetchTreeAsArray(16));
        return ['categories' => $categories];
    }

    /**
     * @Route("/category/{categoryUrl}", name="main_category")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function categoryAction($categoryUrl) {
        $user = $this->get('security.token_storage')->getToken()->getUser();


        $category = $this->getDoctrine()
                ->getRepository("AppBundle:Category")
                ->findOneBy(['urlName' => $categoryUrl]);

        if (null === $category) {
            $this->addFlash('error', 'Špatná kategorie!');
            return $this->redirect($this->generateUrl('main_landingpage'));
        }

        $demands = $this->getDoctrine()
                ->getRepository("AppBundle:Item")
                ->demandsInCategory($category, $user);

        $offers = $this->getDoctrine()
                ->getRepository("AppBundle:Item")
                ->offersInCategory($category, $user);
        dump($demands);

        return ['category' => $category, 'demands' => $demands, 'offers' => $offers];
    }

    /**
     * @Route("/addComment/{item}" ,name="main_addComment")
     * @Security("has_role('ROLE_USER')")   
     */
    public function addCommentAction(Request $request, Item $item) {
        $text = $request->request->get('comment');


        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setOwner($user);
        $comment->setItem($item);
        $comment->setText($text);

        $em->persist($comment);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }

}
