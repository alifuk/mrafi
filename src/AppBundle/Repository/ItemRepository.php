<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class ItemRepository extends EntityRepository
{
    public function findOwnedBy(User $user)
    {
        return $this
            ->createQueryBuilder('i')
            ->select('i')
            ->where('i.owner = :owner')->setParameter('owner', $user)
            ->setMaxResults(7)
            ->getQuery()
            ->getResult()
        ;
    }
}
