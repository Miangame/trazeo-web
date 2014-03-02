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

    /** @ORM\ManyToMany(targetEntity="Trazeo\BaseBundle\Entity\UserExtend", inversedBy="children")
     *  @ORM\JoinColumn(name="userExtend_children", referencedColumnName="id")
     */
    private $userextendchildren;
    
    /** @ORM\ManyToMany(targetEntity="Trazeo\BaseBundle\Entity\Groups", mappedBy="children")
     *  @ORM\JoinColumn(name="groups_children", referencedColumnName="id")
     */
    private $groups;

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
	 * @ORM\Column(name="sex", type="string", columnDefinition="ENUM('H','M')")
	 */
	protected $sex;


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
     * Set userExtend
     *
     * @param string $userExtend
     *
     * @return Children
     */
    public function setUserExtend($userExtend)
    {
        $this->userExtend = $userExtend;

        return $this;
    }

    /**
     * Get userExtend
     *
     * @return string 
     */
    public function getUserExtend()
    {
        return $this->userExtend;
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
     * Set groups
     *
     * @param string $groups
     *
     * @return Children
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return string 
     */
    public function getGroups()
    {
        return $this->groups;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userextend = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userextend
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextend
     *
     * @return Children
     */
    public function addUserextend(\Trazeo\BaseBundle\Entity\UserExtend $userextend)
    {
        $this->userextend[] = $userextend;

        return $this;
    }

    /**
     * Remove userextend
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextend
     */
    public function removeUserextend(\Trazeo\BaseBundle\Entity\UserExtend $userextend)
    {
        $this->userextend->removeElement($userextend);
    }

    /**
     * Add groups
     *
     * @param \Trazeo\BaseBundle\Entity\Groups $groups
     *
     * @return Children
     */
    public function addGroup(\Trazeo\BaseBundle\Entity\Groups $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Trazeo\BaseBundle\Entity\Groups $groups
     */
    public function removeGroup(\Trazeo\BaseBundle\Entity\Groups $groups)
    {
        $this->groups->removeElement($groups);
    }


    /**
     * Add userextendchildren
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextendchildren
     *
     * @return Children
     */
    public function addUserextendchild(\Trazeo\BaseBundle\Entity\UserExtend $userextendchildren)
    {
        $this->userextendchildren[] = $userextendchildren;

        return $this;
    }

    /**
     * Remove userextendchildren
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextendchildren
     */
    public function removeUserextendchild(\Trazeo\BaseBundle\Entity\UserExtend $userextendchildren)
    {
        $this->userextendchildren->removeElement($userextendchildren);
    }

    /**
     * Get userextendchildren
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserextendchildren()
    {
        return $this->userextendchildren;
    }
}
