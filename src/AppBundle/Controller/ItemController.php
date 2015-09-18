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

class ItemController extends Controller {

    /**
     * @Route("/item/deleteDemand/{item}", name="item_deleteDemand")
     */
    public function deleteDemandAction(Item $item) {

        if (!$item) {
            throw $this->createNotFoundException('No item found');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($item);
        $em->flush();
        
        $this->addFlash('info', 'Poptávka smazána');
        
        return $this->redirect($this->generateUrl('main_demand'));
    }

}
