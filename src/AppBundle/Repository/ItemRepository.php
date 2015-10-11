<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Item;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class ItemRepository extends EntityRepository {

    public function findOwnedBy(User $user) {
        return $this
                        ->createQueryBuilder('i')
                        ->select('i')
                        ->andWhere('i.owner = :owner')->setParameter('owner', $user)
                        ->andWhere('i.deleted = 0')
                        ->setMaxResults(14)
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function findPersonificatedOffersFor(User $user) {
        return $this
                        ->createQueryBuilder('i')
                        ->select('i')
                        ->andWhere('i.owner <> :owner')->setParameter('owner', $user)
                        ->andWhere('i.deleted = 0')
                        ->andWhere('i.completed = 0')
                        ->andWhere('i.type = :type')->setParameter('type', Item::TYPE_OFFER)
                        ->setMaxResults(14)
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function findPersonificatedDemandsFor(User $user) {
        return $this
                        ->createQueryBuilder('i')
                        ->select('i')
                        ->andWhere('i.owner <> :owner')->setParameter('owner', $user)
                        ->andWhere('i.deleted = 0')
                        ->andWhere('i.completed = 0')
                        ->andWhere('i.type = :type')->setParameter('type', Item::TYPE_DEMAND)
                        ->setMaxResults(14)
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function findOffersOf(User $user) {
        return $this->itemsOfUser($user, Item::TYPE_OFFER);
    }

    public function findDemandsOf(User $user) {
        return $this->itemsOfUser($user, Item::TYPE_DEMAND);
    }

    public function itemsOfUser(User $user, $type) {
        return $this
                        ->createQueryBuilder('i')
                        ->select('i')
                        ->andWhere('i.owner = :owner')->setParameter('owner', $user)
                        ->andWhere('i.deleted = 0')
                        ->andWhere('i.type = :type')->setParameter('type', $type)
                        ->setMaxResults(14)
                        ->orderBy('i.id', 'DESC')
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function demandsInCategory(Category $category) {
        return $this->itemsInCategory($category, Item::TYPE_DEMAND);
    }

    public function offersInCategory(Category $category) {
        return $this->itemsInCategory($category, Item::TYPE_OFFER);
    }

    public function itemsInCategory(Category $category, $type) {
        return $this
                        ->createQueryBuilder('i')
                        ->select('i')
                        ->andWhere('i.deleted = 0')
                        ->andWhere('i.completed = 0')
                        ->andWhere('i.type = :type')->setParameter('type', $type)
                        ->leftJoin('i.category', 'c', 'i.category == c.id')                
                        ->andWhere('c.lft >= :categoryLft')->setParameter('categoryLft', $category->getLeftValue())
                        ->andWhere('c.rgt <= :categoryRgt')->setParameter('categoryRgt', $category->getRightValue())
                        ->andWhere('c.root = :categoryRoot')->setParameter('categoryRoot', $category->getRootValue())               
                        ->setMaxResults(14)
                        ->orderBy('i.id', 'DESC')
                        ->getQuery()
                        ->getResult()
        ;
    }

}
