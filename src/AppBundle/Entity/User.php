<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User implements UserInterface {

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
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="adress1", type="string", length=255, nullable=true)
     */
    private $adress1;

    /**
     * @var string
     *
     * @ORM\Column(name="adress2", type="string", length=255, nullable=true)
     */
    private $adress2;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="ico", type="string", length=20)
     */
    private $ico;

    /**
     * @var string
     *
     * @ORM\Column(name="dic", type="string", length=20, nullable=true)
     */
    private $dic;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=20, nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=20, nullable=true)
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundImage", type="string", length=20, nullable=true)
     */
    private $backgroundImage;

    /**
     * @var string
     *
     * @ORM\Column(name="profileImage", type="string", length=20, nullable=true)
     */
    private $profileImage;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="owner")
     * */
    private $items;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="owner")
     * */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Gathering", inversedBy="users")
     * @ORM\JoinTable(name="users_gatherings")
     * */
    private $gatherings;

    /**
     * @ORM\OneToMany(targetEntity="Distance", mappedBy="user_from")
     * */
    private $distanceFrom;
    
    /**
     * @ORM\OneToMany(targetEntity="Distance", mappedBy="user_to")
     * */
    private $distanceTo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="distance", type="string", length=20, nullable=true)
     */
    private $distance;
    
    
    
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set adress1
     *
     * @param string $adress1
     * @return User
     */
    public function setAdress1($adress1) {
        $this->adress1 = $adress1;

        return $this;
    }

    /**
     * Get adress1
     *
     * @return string 
     */
    public function getAdress1() {
        return $this->adress1;
    }

    /**
     * Set adress2
     *
     * @param string $adress2
     * @return User
     */
    public function setAdress2($adress2) {
        $this->adress2 = $adress2;

        return $this;
    }

    /**
     * Get adress2
     *
     * @return string 
     */
    public function getAdress2() {
        return $this->adress2;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set date
     *
     * @param DateTime $date
     * @return User
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set ico
     *
     * @param string $ico
     * @return User
     */
    public function setIco($ico) {
        $this->ico = $ico;

        return $this;
    }

    /**
     * Get ico
     *
     * @return string 
     */
    public function getIco() {
        return $this->ico;
    }

    /**
     * Set dic
     *
     * @param string $dic
     * @return User
     */
    public function setDic($dic) {
        $this->dic = $dic;

        return $this;
    }

    /**
     * Get dic
     *
     * @return string 
     */
    public function getDic() {
        return $this->dic;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return User
     */
    public function setLat($lat) {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return User
     */
    public function setLng($lng) {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set backgroundImage
     *
     * @param string $backgroundImage
     * @return User
     */
    public function setBackgroundImage($backgroundImage) {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    /**
     * Get backgroundImage
     *
     * @return string 
     */
    public function getBackgroundImage() {
        return $this->backgroundImage;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getSalt() {
        return md5(uniqid());
    }

    public function getUsername() {
        return $this->email;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->name,
            $this->password,
                // see section on salt below
                // $this->salt,
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->id,
                $this->name,
                $this->password,
                // see section on salt below
                // $this->salt
                ) = unserialize($serialized);
    }

    /**
     * Set profileImage
     *
     * @param string $profileImage
     * @return User
     */
    public function setProfileImage($profileImage) {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return string 
     */
    public function getProfileImage() {
        return $this->profileImage;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gatherings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add items
     *
     * @param \AppBundle\Entity\Item $items
     * @return User
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
     * Add gatherings
     *
     * @param \AppBundle\Entity\Gathering $gatherings
     * @return User
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

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comments
     */
    public function removeComment(\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set distance
     *
     * @param string $distance
     * @return User
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return string 
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Add distanceFrom
     *
     * @param \AppBundle\Entity\Distance $distanceFrom
     * @return User
     */
    public function addDistanceFrom(\AppBundle\Entity\Distance $distanceFrom)
    {
        $this->distanceFrom[] = $distanceFrom;

        return $this;
    }

    /**
     * Remove distanceFrom
     *
     * @param \AppBundle\Entity\Distance $distanceFrom
     */
    public function removeDistanceFrom(\AppBundle\Entity\Distance $distanceFrom)
    {
        $this->distanceFrom->removeElement($distanceFrom);
    }

    /**
     * Get distanceFrom
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistanceFrom()
    {
        return $this->distanceFrom;
    }

    /**
     * Add distanceTo
     *
     * @param \AppBundle\Entity\Distance $distanceTo
     * @return User
     */
    public function addDistanceTo(\AppBundle\Entity\Distance $distanceTo)
    {
        $this->distanceTo[] = $distanceTo;

        return $this;
    }

    /**
     * Remove distanceTo
     *
     * @param \AppBundle\Entity\Distance $distanceTo
     */
    public function removeDistanceTo(\AppBundle\Entity\Distance $distanceTo)
    {
        $this->distanceTo->removeElement($distanceTo);
    }

    /**
     * Get distanceTo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistanceTo()
    {
        return $this->distanceTo;
    }
}
