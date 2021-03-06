<?php

namespace Trazeo\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use JMS\Serializer\Annotation\Exclude;
/**
 * Entity Children
 *
 * @ORM\Table("e_child")
 * @ORM\Entity(repositoryClass="EChildRepository")
 */
class EChild extends AbstractEntity
{
    const GENDER_BOY = "boy";
    const GENDER_GIRL = "girl";

	use ORMBehaviors\Timestampable\Timestampable;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** @ORM\ManyToMany(targetEntity="UserExtend", inversedBy="childs")
     *  @Exclude
     */
    protected $userextendchilds;
    
    /**
     *  @ORM\ManyToMany(targetEntity="EGroup", mappedBy="childs")
     *  @Exclude
     */
    protected $groups;

    /**
     * @ORM\ManyToOne(targetEntity="ERide", inversedBy="childs")
     * @ORM\JoinColumn(name="ride_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     **/
    protected $ride;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=255)
     */
    protected $nick;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBirth", type="date", nullable=true)
     */
    protected $dateBirth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    protected $visibility;
    
    /** @ORM\OneToMany(targetEntity="EChildInvite",  mappedBy="child")
     * @var unknown
     * @Exclude
     */
    protected $inviteChild;


	/**
	 * @ORM\Column(name="gender", type="string", nullable=true)
	 */
	protected $gender;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="selected", type="boolean", nullable=true)
	 */
	protected $selected;


    /**
     * @ORM\Column(name="scholl", type="string", nullable=true)
     */
    protected $scholl;

    /**
     *  @ORM\ManyToMany(targetEntity="Medal", mappedBy="childs", cascade={"persist"})
     *  @Exclude
     */
    protected $medals;

    protected $emailParent;

    protected $mobileParent;

    protected $weekNotActivity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastMedals;

    public function setLastMedals($lastMedals) {
        $this->lastMedals = $lastMedals;
    }

    public function getLastMedals() {
        return $this->lastMedals;
    }

    public function getWeekNotActivity() {
        $em = $this->getEntityManager();
        $repositoryEvent = $em->getRepository('TrazeoBaseBundle:EEvent');
        /** @var EEvent $last_event */
        $last_event = $repositoryEvent->findOneBy(array(
            'data' => $this->getId() . "/" . $this->getNick(),
            'action' => 'in',
        ), array('createdAt' => 'DESC'));

        if ($last_event == null) return "Nunca";

        $today = new \DateTime();

        /** @var \DateInterval $diff */
        $diff = $last_event->getCreatedAt()->diff($today);

        return round($diff->days / 7);
    }

    public function getEmailParent() {
        /** @var UserExtend $ue */
        foreach($this->userextendchilds as $ue) {
            if ($ue->getUser()->getEmail() != "") $this->emailParent = $ue->getUser()->getEmail();
        }
        return $this->emailParent;
    }

    public function getMobileParent() {
        /** @var UserExtend $ue */
        foreach($this->userextendchilds as $ue) {
            if ($ue->getMobile() != "") $this->mobileParent = $ue->getMobile();
        }
        return $this->mobileParent;
    }
        
       
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
	$this->lastMedals = new \DateTime();
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

    /**
     * Add inviteChild
     *
     * @param \Trazeo\BaseBundle\Entity\EChildInvite $inviteChild
     *
     * @return EChild
     */
    public function addInviteChild(\Trazeo\BaseBundle\Entity\EChildInvite $inviteChild)
    {
        $this->inviteChild[] = $inviteChild;

        return $this;
    }

    /**
     * Remove inviteChild
     *
     * @param \Trazeo\BaseBundle\Entity\EChildInvite $inviteChild
     */
    public function removeInviteChild(\Trazeo\BaseBundle\Entity\EChildInvite $inviteChild)
    {
        $this->inviteChild->removeElement($inviteChild);
    }

    /**
     * Get inviteChild
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInviteChild()
    {
        return $this->inviteChild;
    }

    /**
     * Set selected
     *
     * @param boolean $selected
     *
     * @return EChild
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return boolean 
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set scholl
     *
     * @param string $scholl
     *
     * @return EChild
     */
    public function setScholl($scholl)
    {
        $this->scholl = $scholl;

        return $this;
    }

    /**
     * Get scholl
     *
     * @return string
     */
    public function getScholl()
    {
        return $this->scholl;
    }

    /**
     * Set ride
     *
     * @param \Trazeo\BaseBundle\Entity\ERide $ride
     * @return EChild
     */
    public function setRide(\Trazeo\BaseBundle\Entity\ERide $ride = null)
    {
        $this->ride = $ride;

        return $this;
    }

    /**
     * Get ride
     *
     * @return \Trazeo\BaseBundle\Entity\ERide 
     */
    public function getRide()
    {
        return $this->ride;
    }

    /**
     * Devuelve la edad en años del niño/a
     */
    public function getYears() {
        $now = new \DateTime();
        if ($this->getDateBirth() == null) return 0;
        $diff = $now->getTimestamp() - $this->getDateBirth()->getTimestamp();
        $year_stamp = 31536000;
        $ret = $diff / $year_stamp;
        return round($ret, 0);
    }

    /**
     * Add medals
     *
     * @param \Trazeo\BaseBundle\Entity\Medal $medals
     * @return EChild
     */
    public function addMedal(\Trazeo\BaseBundle\Entity\Medal $medals)
    {
        $this->medals[] = $medals;
        $medals->addChild($this);
	$this->setLastMedals(new \DateTime());

        return $this;
    }

    /**
     * Remove medals
     *
     * @param \Trazeo\BaseBundle\Entity\Medal $medals
     */
    public function removeMedal(\Trazeo\BaseBundle\Entity\Medal $medals)
    {
        $this->medals->removeElement($medals);
        $medals->removeChild($this);
    }

    /**
     * Get medals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedals()
    {
        return $this->medals;
    }
}
