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

/**
 * @Route("/admin")
 */
class AdminController extends Controller {

    /**
     * @Route("/", name="admin")
     * @Template()
     */
    public function adminAction() {

        $em = $this->getDoctrine()->getManager();
        $config = new Config($em, 'Appbundle\Entity\Category');
        $nsm = new Manager($config);
        
        //FOR GETTING TREE
        $rootNode = $nsm->fetchTree(15);
        
        /*$category = new Category();
        $category->setName('Obiloviny');
        $category->setUrlName('obiloviny');*/

        // FOR ADDING ROOT CATEGORY
        //$rootNode = $nsm->createRoot($category);
        
        // FOR ADDING CHILD
        //$rootNode->addChild($category);
        //$this->addFlash("success", "child node přidán");
        
        dump($nsm->fetchTreeAsArray(15));
        return ["tree" => $nsm->fetchTreeAsArray(15)];
    }

}
