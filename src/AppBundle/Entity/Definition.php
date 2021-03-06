<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Definition
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Definition {

    const TYPE_STRING = 1;
    const TYPE_NUMBER = 2;
    const TYPE_BOOLEAN = 3;
    const TYPE_SELECT = 4;
    

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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="placeholder", type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=50)
     */
    private $unit;

    /**
     * @var array
     *
     * @ORM\Column(name="options", type="array")
     */
    private $options = [];

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="definitions")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $category;
    
    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Param", mappedBy="definition")
     */
    private $parameters;
    

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
     * @return Definition
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
     * @return Definition
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
     * Set unit
     *
     * @param string $unit
     * @return Definition
     */
    public function setUnit($unit) {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit() {
        return $this->unit;
    }


    /**
     * Set options
     *
     * @param array $options
     * @return Definition
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return array 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Definition
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
    
    

    /**
     * Set placeholder
     *
     * @param string $placeholder
     * @return Definition
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return string 
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
}
