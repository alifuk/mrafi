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
     * @Route("/create/{typeStr}/{responceTo}", name="item_create", defaults={"responceTo" = null})
     * @Template()
     */
    public function itemCreateAction(Request $request, $typeStr, Item $responceTo = null) {
        $type = Item::typeToInt($typeStr);

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
            $prise = $request->request->get('prise');

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
            $item->setType($type);
            $item->setResponceTo($responceTo);
            $item->setPublic($public);
            $item->setPrice($price);
            $item->setCategory($categoryObj);
            $item->setNote($note);
            $item->setDeleted(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();


            if ($type === Item::TYPE_DEMAND) {
                $this->addFlash('success', 'Poptávka úspěšně vytvořena');
                return $this->redirect($this->generateUrl('main_demand'));
            } else {
                $this->addFlash('success', 'Nabídka úspěšně vytvořena');
                return $this->redirect($this->generateUrl('main_offer'));
            }
        }
        
        dump($responceTo);
        return ['categories' => $categories, 'type' => Item::intToType($type), 'responceTo' => $responceTo];
    }

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

    /**
     * @Route("/item/markAsDone/{item}", name="item_markAsDone")
     */
    public function markAsDoneAction(Item $item) {

        if (!$item) {
            throw $this->createNotFoundException('No item found');
        }

        $item->setCompleted(true);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($item);
        $em->flush();

        $this->addFlash('success', 'Označeno jako vyřízené');

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

}
