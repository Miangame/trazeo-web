<?php

namespace Trazeo\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Entity Children
 *
 * @ORM\Table("e_child")
 * @ORM\Entity
 */
class EChild
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** @ORM\ManyToMany(targetEntity="UserExtend", mappedBy="childs")
     */
    protected $userextendchilds;
    
    /** @ORM\ManyToMany(targetEntity="EGroup", inversedBy="childs")
     */
    protected $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=255)
     */
    protected $nick;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBirth", type="datetime")
     */
    protected $dateBirth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    protected $visibility;


	/**
	 * @ORM\Column(name="gender", type="string")
	 */
	protected $gender;
    
       
    public function __toString(){
    	if($this->nick == "")
    		return (string)$this->id;
    	return $this->nick;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userextendchildren = new \Doctrine\Common\Collections\ArrayCollection();
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nick
     *
     * @param string $nick
     *
     * @return EChild
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string 
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Set dateBirth
     *
     * @param \DateTime $dateBirth
     *
     * @return EChild
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return \DateTime 
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set visibility
     *
     * @param boolean $visibility
     *
     * @return EChild
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return EChild
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Add userextendchilds
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextendchilds
     *
     * @return EChild
     */
    public function addUserextendchild(\Trazeo\BaseBundle\Entity\UserExtend $userextendchilds)
    {
        $this->userextendchilds[] = $userextendchilds;

        return $this;
    }

    /**
     * Remove userextendchilds
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextendchilds
     */
    public function removeUserextendchild(\Trazeo\BaseBundle\Entity\UserExtend $userextendchilds)
    {
        $this->userextendchilds->removeElement($userextendchilds);
    }

    /**
     * Get userextendchilds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserextendchilds()
    {
        return $this->userextendchilds;
    }

    /**
     * Add groups
     *
     * @param \Trazeo\BaseBundle\Entity\EGroup $groups
     *
     * @return EChild
     */
    public function addGroup(\Trazeo\BaseBundle\Entity\EGroup $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Trazeo\BaseBundle\Entity\EGroup $groups
     */
    public function removeGroup(\Trazeo\BaseBundle\Entity\EGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}