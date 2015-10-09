<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class ItemController extends Controller {

    /**
     * @Route("/create/{typeStr}/{responceTo}", name="item_create", defaults={"responceTo" = null})
     * @Template()
     */
    public function itemCreateAction(Request $request, $typeStr, $responceTo) {
        $type = Item::typeToInt($typeStr);

        if (null !== $responceTo) {
            $responceTo = $this->getDoctrine()
                    ->getRepository('AppBundle:Item')
                    ->find($responceTo);
        }


        $user = $this->get('security.token_storage')->getToken()->getUser();



        if ($request->isMethod('POST')) {
            dump("ukladani");

            $name = $request->request->get('name');
            $public = $request->request->get('public');
            $note = $request->request->get('note');
            $category = $request->request->get('category');
            $price = $request->request->get('price');
            $validUntil = date("Y-m-d",strtotime($request->request->get('validUntil')));
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
            dump($validUntil);
            $item->setValidUntil(new \DateTime($validUntil));
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
        
        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->findAll();
        return ['categories' => $categories, 'type' => Item::intToType($type), 'responceTo' => $responceTo];
    }

    /**
     * @Route("/item/delete/{item}", name="item_deleteDemand")
     */
    public function deleteDemandAction(Item $item) {

        if (!$item) {
            throw $this->createNotFoundException('No item found');
        }

        $type = $item->getType();
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($item);
        $em->flush();

        if ($type === Item::TYPE_DEMAND) {
            $this->addFlash('info', 'Nabídka smazána');
        } else {
            $this->addFlash('info', 'Poptávka smazána');
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
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
