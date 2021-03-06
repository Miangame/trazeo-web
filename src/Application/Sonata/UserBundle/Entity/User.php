<?php

namespace Application\Sonata\UserBundle\Entity;

#use FOS\UserBundle\Entity\User as BaseUser;
use Sonata\UserBundle\Entity\BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="foss_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

    }
    
    /**
     * @ORM\OneToOne(targetEntity="Sopinet\UserBundle\Entity\SopinetUserExtend", mappedBy="user")
     * @ORM\JoinColumn(name="sopinetuserextend_id", referencedColumnName="id")
     */
    private $sopinetuserextend;

    public function setSopinetUserExtend(\Sopinet\UserBundle\Entity\SopinetUserExtend $sopinetuserextend = null)
    {
    	$this->sopinetuserextend = $sopinetuserextend;
    
    	return $this;
    }
    
    /**
     *
     * @return \Sopinet\UserBundle\Entity\SopinetUserExtend $sopinetuserextend
     */
    public function getSopinetUserExtend()
    {
    	return $this->sopinetuserextend;
    }    

    /**
     * @ORM\OneToOne(targetEntity="Trazeo\BaseBundle\Entity\UserExtend", mappedBy="user")
     * @ORM\JoinColumn(name="userextend_id", referencedColumnName="id")
     */
    private $userextend;
    
    /**
     * Set address
     *
     * @param \Trazeo\BaseBundle\Entity\UserExtend $userextend
     * @return Order
     */
    public function setUserExtend(\Trazeo\BaseBundle\Entity\UserExtend $userextend = null)
    {
    	$this->userextend = $userextend;
    
    	return $this;
    }
    
    /**
     * Get address
     *
     * @return \Trazeo\BaseBundle\Entity\UserExtend
     */
    public function getUserExtend()
    {
    	return $this->userextend;
    }

    public function getCreatedAt() {
        if ($this->createdAt->getTimestamp() === false || $this->createdAt->getTimestamp() < 0) {
            $childs = $this->getUserExtend()->getChilds();
            if (count($childs) > 0) {
                $date = $childs[0]->getCreatedAt();
                if ($date != null) return $date;
            }
        }
        return $this->createdAt;
    }
}