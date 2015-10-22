<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Distance
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Distance
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="distanceFrom")
     * @ORM\JoinColumn(name="user_from", referencedColumnName="id")
     **/
    private $userFrom;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="distanceTo")
     * @ORM\JoinColumn(name="user_to", referencedColumnName="id")
     **/
    private $userTo;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="distance", type="integer", nullable=true)
     */
    private $distance;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
