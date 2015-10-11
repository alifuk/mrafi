<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AJAXController extends Controller {

    /**
     * @Route("/definitions", name="ajax_definitions")
     * @Template("AppBundle::Form/definitions.html.twig")
     */
    public function deleteDemandAction() {
        $request = Request::createFromGlobals();
        $categoryId = $request->request->get('category', null);
        if ($categoryId == null) {
            return new Response('');
        }

        $definitions = $this->getDoctrine()
                ->getRepository("AppBundle:Definition")
                ->findBy(["category" => $categoryId]);



        return [ 'definitions' => $definitions];
    }

}
