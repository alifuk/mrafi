<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="urlName", type="string", length=255, unique=true)
     */
    private $urlName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="childs")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     **/
    private $childs;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
     **/
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="category")
     **/
    private $parameters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Gathering", mappedBy="categories")
     **/
    private $gatherings;
    

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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url-name
     *
     * @param string $urlName
     * @return Category
     */
    public function setUrlName($urlName)
    {
        $this->urlName = $urlName;

        return $this;
    }

    /**
     * Get url-name
     *
     * @return string 
     */
    public function getUrlName()
    {
        return $this->urlName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add childs
     *
     * @param \AppBundle\Entity\Category $childs
     * @return Category
     */
    public function addChild(\AppBundle\Entity\Category $childs)
    {
        $this->childs[] = $childs;

        return $this;
    }

    /**
     * Remove childs
     *
     * @param \AppBundle\Entity\Category $childs
     */
    public function removeChild(\AppBundle\Entity\Category $childs)
    {
        $this->childs->removeElement($childs);
    }

    /**
     * Get childs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return Category
     */
    public function addItem(\AppBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Item $items
     */
    public function removeItem(\AppBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add parameters
     *
     * @param \AppBundle\Entity\Parameter $parameters
     * @return Category
     */
    public function addParameter(\AppBundle\Entity\Parameter $parameters)
    {
        $this->parameters[] = $parameters;

        return $this;
    }

    /**
     * Remove parameters
     *
     * @param \AppBundle\Entity\Parameter $parameters
     */
    public function removeParameter(\AppBundle\Entity\Parameter $parameters)
    {
        $this->parameters->removeElement($parameters);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Add gatherings
     *
     * @param \AppBundle\Entity\Gathering $gatherings
     * @return Category
     */
    public function addGathering(\AppBundle\Entity\Gathering $gatherings)
    {
        $this->gatherings[] = $gatherings;

        return $this;
    }

    /**
     * Remove gatherings
     *
     * @param \AppBundle\Entity\Gathering $gatherings
     */
    public function removeGathering(\AppBundle\Entity\Gathering $gatherings)
    {
        $this->gatherings->removeElement($gatherings);
    }

    /**
     * Get gatherings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGatherings()
    {
        return $this->gatherings;
    }
}
