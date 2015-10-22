<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Param;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends Controller {

    /**
     * @Route("/create/{typeStr}", name="item_create")
     * @Template("AppBundle:Item:ItemCreate.html.twig")
     */
    public function itemCreateAction(Request $request, $typeStr) {
        
        $responceTo = null;
        if(($rt = $request->query->get('responceTo')) != null ){
            $responceTo = $this->getDoctrine()->getRepository('AppBundle:Item')->find($rt);            
        }       
        
        return $this->itemCreate($request, $typeStr, $responceTo);
    }

    public function itemCreate(Request $request, $typeStr, $responceTo) {
        $type = Item::typeToInt($typeStr);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($request->isMethod('POST')) {
            dump("ukladani");

            $name = $request->request->get('name');
            $public = $request->request->get('public');
            $note = $request->request->get('note');
            $category = $request->request->get('category');
            $price = $request->request->get('price');
            $parameters = $request->request->get('parameter');
            $parameters = $request->request->get('parameter') == null ? [] : $request->request->get('parameter');
            dump($parameters);

            $validUntil = date("Y-m-d", strtotime($request->request->get('validUntil')));

            $public = ($public === null) ? false : true;
            dump($request);

            $categoryObj = $this->getDoctrine()
                    ->getRepository('AppBundle:Category')
                    ->find($category);


            $item = new Item();
            $item->setName($name);
            $item->setOwner($user);
            $item->setType($type);
            dump($type);
            $item->setResponceTo($responceTo);
            $item->setPublic($public);
            $item->setPrice($price);
            $item->setCategory($categoryObj);
            dump($validUntil);
            $item->setValidUntil(new \DateTime($validUntil));
            $item->setNote($note);
            $item->setDeleted(0);
            $em = $this->getDoctrine()->getManager();
            foreach ($parameters as $definitionId => $value) {
                $definition = $this->getDoctrine()->getRepository("AppBundle:Definition")->find($definitionId);
                $parameter = new Param();
                $parameter->setDefinition($definition);
                $parameter->setItem($item);
                $parameter->setValue($value);
                $em->persist($parameter);
                $item->addParam($parameter);
            }


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
