<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class Item {

    const TYPE_OFFER = 1;
    const TYPE_DEMAND = 1;
    const TYPE_AUCTION = 1;

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
     * @ORM\Column(name="note", type="string", length=1000, nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="items")
     * @ORM\JoinColumn(name="owner", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     *  @ORM\PrePersist 
     */
    public function doStuffOnPrePersist() {
        $this->dateCreated = new DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Item
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Item
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Item
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted() {
        return $this->deleted;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Item
     */
    public function setPublic($public) {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic() {
        return $this->public;
    }

    /**
     * Get dateCreated
     *
     * @return DateTime 
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * Set owner
     *
     * @param integer $owner
     * @return Item
     */
    public function setOwner($owner) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return integer 
     */
    public function getOwner() {
        return $this->owner;
    }


    /**
     * Set note
     *
     * @param string $note
     * @return Item
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Item
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Item
     */
    public function setCategory(\AppBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
