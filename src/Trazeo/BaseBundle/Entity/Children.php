<?php

namespace Trazeo\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Children
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Children
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
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", mappedBy="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=255)
     */
    private $nick;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBirth", type="datetime")
     */
    private $dateBirth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    private $visibility;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", mappedBy="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $groups;

    /**
     * @ORM\Column(name="sex", type="string", columnDefinition="ENUM('H','M')")
     */
    private $sex;


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
     * Set User
     */
    public function setUser(Application\Sonata\UserBundle\Entity\User $user) {
    	$this->user = $user;
    }
    
    /**
     * Get User
     *
     * @return string
     */
    public function getUser() {
    	return $this->User;
    }

    /**
     * Set nick
     *
     * @param string $nick
     *
     * @return Children
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
     * @return Children
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
     * @return Children
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
     * Set Groups
     */
    public function setGroup(Application\Sonata\UserBundle\Entity\Group $groups) {
    	$this->groups = $groups;
    }
    
    /**
     * Get Groups
     *
     * @return string
     */
    public function getGroups() {
    	return $this->Groups;
    }
    
    
    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Children
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }
    
    
    public function __toString() {
    	return $this->getNick();
    }
    
}

