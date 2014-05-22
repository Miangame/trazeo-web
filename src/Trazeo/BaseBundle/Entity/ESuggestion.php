<?php

namespace Trazeo\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * Entity ESuggestion
 *
 * @ORM\Table("e_suggestion")
 * @ORM\Entity
 */
class ESuggestion
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="rule", type="string", length=255)
	 */
	protected $rule;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="text", type="string", length=255)
	 */
	protected $text;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="element", type="string", length=255)
	 */
	protected $element;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="order", type="integer", length=10)
	 */
	protected $order;	

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
     * Set rule
     *
     * @param string $rule
     *
     * @return ESuggestion
     */
    public function setRule($rule)
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * Get rule
     *
     * @return string 
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return ESuggestion
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return ESuggestion
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set element
     *
     * @param string $element
     *
     * @return ESuggestion
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return string 
     */
    public function getElement()
    {
        return $this->element;
    }
}
