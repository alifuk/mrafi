<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Param
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Param
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;
    
    
    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Definition", inversedBy="parameters")
     * @ORM\JoinColumn(name="definition", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $definition;
    
    
    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="params")
     * @ORM\JoinColumn(name="item", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $item;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Parameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set definition
     *
     * @param \AppBundle\Entity\Definition $definition
     * @return Parameter
     */
    public function setDefinition(\AppBundle\Entity\Definition $definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return \AppBundle\Entity\Definition 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set item
     *
     * @param \AppBundle\Entity\Item $item
     * @return Parameter
     */
    public function setItem(\AppBundle\Entity\Item $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \AppBundle\Entity\Item 
     */
    public function getItem()
    {
        return $this->item;
    }
}
