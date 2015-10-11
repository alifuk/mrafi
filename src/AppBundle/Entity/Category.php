<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category implements MultipleRootNode {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $root;

    /**
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @ORM\Column(type="string", length=64)
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
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
     * */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="Gathering", mappedBy="categories")
     * */
    private $gatherings;

    /**
     * @ORM\OneToMany(targetEntity="Definition", mappedBy="category")
     * */
    private $definitions;

    /**
     * Set url-name
     *
     * @param string $urlName
     * @return Category
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;

        return $this;
    }

    /**
     * Get url-name
     *
     * @return string 
     */
    public function getUrlName() {
        return $this->urlName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Constructor
     */
    public function __construct() {
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
    public function addChild(\AppBundle\Entity\Category $childs) {
        $this->childs[] = $childs;

        return $this;
    }

    /**
     * Remove childs
     *
     * @param \AppBundle\Entity\Category $childs
     */
    public function removeChild(\AppBundle\Entity\Category $childs) {
        $this->childs->removeElement($childs);
    }

    /**
     * Get childs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChilds() {
        return $this->childs;
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return Category
     */
    public function addItem(\AppBundle\Entity\Item $items) {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Item $items
     */
    public function removeItem(\AppBundle\Entity\Item $items) {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Add gatherings
     *
     * @param \AppBundle\Entity\Gathering $gatherings
     * @return Category
     */
    public function addGathering(\AppBundle\Entity\Gathering $gatherings) {
        $this->gatherings[] = $gatherings;

        return $this;
    }

    /**
     * Remove gatherings
     *
     * @param \AppBundle\Entity\Gathering $gatherings
     */
    public function removeGathering(\AppBundle\Entity\Gathering $gatherings) {
        $this->gatherings->removeElement($gatherings);
    }

    /**
     * Get gatherings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGatherings() {
        return $this->gatherings;
    }

    /**
     * Add definitions
     *
     * @param \AppBundle\Entity\Definition $definitions
     * @return Category
     */
    public function addDefinition(\AppBundle\Entity\Definition $definitions) {
        $this->definitions[] = $definitions;

        return $this;
    }

    /**
     * Remove definitions
     *
     * @param \AppBundle\Entity\Definition $definitions
     */
    public function removeDefinition(\AppBundle\Entity\Definition $definitions) {
        $this->definitions->removeElement($definitions);
    }

    /**
     * Get definitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDefinitions() {
        return $this->definitions;
    }

    public function getId() {
        return $this->id;
    }

    public function getLeftValue() {
        return $this->lft;
    }

    public function setLeftValue($lft) {
        $this->lft = $lft;
    }

    public function getRightValue() {
        return $this->rgt;
    }

    public function setRightValue($rgt) {
        $this->rgt = $rgt;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function __toString() {
        return $this->name;
    }

    public function getRootValue() {
        return $this->root;
    }

    public function setRootValue($root) {
        $this->root = $root;
    }

}
