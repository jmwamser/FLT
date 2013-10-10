<?php

namespace UCrm\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TerritoryHistory
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TerritoryHistory
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
     * @var integer
     *
     * @ORM\Column(name="territory_id", type="integer", nullable=true)
     */
    private $territoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="checked_out_by", type="integer", nullable=true)
     */
    private $checkedOutBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_out_on", type="date", nullable=true)
     */
    private $checkedOutOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_in_on", type="date", nullable=true)
     */
    private $checkedInOn;

    /**
     * @ORM\ManyToOne(targetEntity="Territory", inversedBy="history")
     * @ORM\JoinColumn(name="territory_id", referencedColumnName="id")
     */
    private $territory;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="territoryHistory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set territoryId
     *
     * @param integer $territoryId
     * @return TerritoryHistory
     */
    public function setTerritoryId($territoryId)
    {
        $this->territoryId = $territoryId;
    
        return $this;
    }

    /**
     * Get territoryId
     *
     * @return integer 
     */
    public function getTerritoryId()
    {
        return $this->territoryId;
    }

    /**
     * Set checkedOutBy
     *
     * @param integer $checkedOutBy
     * @return TerritoryHistory
     */
    public function setCheckedOutBy($checkedOutBy)
    {
        $this->checkedOutBy = $checkedOutBy;
    
        return $this;
    }

    /**
     * Get checkedOutBy
     *
     * @return integer 
     */
    public function getCheckedOutBy()
    {
        return $this->checkedOutBy;
    }

    /**
     * Set checkedOutOn
     *
     * @param \DateTime $checkedOutOn
     * @return TerritoryHistory
     */
    public function setCheckedOutOn($checkedOutOn)
    {
        $this->checkedOutOn = $checkedOutOn;
    
        return $this;
    }

    /**
     * Get checkedOutOn
     *
     * @return \DateTime 
     */
    public function getCheckedOutOn()
    {
        return $this->checkedOutOn;
    }

    /**
     * Set checkedInOn
     *
     * @param \DateTime $checkedInOn
     * @return TerritoryHistory
     */
    public function setCheckedInOn($checkedInOn)
    {
        $this->checkedInOn = $checkedInOn;
    
        return $this;
    }

    /**
     * Get checkedInOn
     *
     * @return \DateTime 
     */
    public function getCheckedInOn()
    {
        return $this->checkedInOn;
    }

    /**
     * Set territory
     *
     * @param \UCrm\CoreBundle\Entity\Territory $territory
     * @return TerritoryHistory
     */
    public function setTerritory(\UCrm\CoreBundle\Entity\Territory $territory = null)
    {
        $this->territory = $territory;
    
        return $this;
    }

    /**
     * Get territory
     *
     * @return \UCrm\CoreBundle\Entity\Territory 
     */
    public function getTerritory()
    {
        return $this->territory;
    }

    /**
     * Set user
     *
     * @param \UCrm\CoreBundle\Entity\User $user
     * @return TerritoryHistory
     */
    public function setUser(\UCrm\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \UCrm\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}