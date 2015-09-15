<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Parameter
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="section", type="string", length=100)
     */
    private $section;

    

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="parameters")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false)
     */
    private $category;
    


}
