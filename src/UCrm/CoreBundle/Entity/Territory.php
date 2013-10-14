<?php

namespace UCrm\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Territory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UCrm\CoreBundle\Entity\TerritoryRepository")
 */
class Territory
{

    const StatusNone = 0;

    const StatusCheckedOutNotRecorded = 1;

    const StatusCheckedOutRecorded = 2;

    const StatusUnworked = 3;

    const StatusWorked = 4;

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
     * @ORM\Column(name="coords", type="json_array", nullable=true)
     */
    private $coords = null;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code = null;

    /**
     * @var integer
     * 
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_out_on", type="datetime", nullable=true)
     */
    private $checkedOutOn = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_in_on", type="datetime", nullable=true)
     */
    private $checkedInOn = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_out_to", type="integer", nullable=true)
     */
    private $checkedOutTo = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="territory")
     * @ORM\JoinColumn(name="checked_out_to", referencedColumnName="id")
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
     * Set coords
     *
     * @param string $coords
     * @return Territory
     */
    public function setCoords($coords)
    {
        $this->coords = $coords;
    
        return $this;
    }

    /**
     * Get coords
     *
     * @return string 
     */
    public function getCoords()
    {
        return $this->coords;
    }

    public function hasCoords()
    {
        return !empty($this->coords);
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Territory
     */
    public function setMap($map)
    {
        $this->map = $map;
    
        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set checkoutOutBy
     *
     * @param integer $checkoutOutBy
     * @return Territory
     */
    public function setCheckoutOutBy($checkoutOutBy)
    {
        $this->checkoutOutBy = $checkoutOutBy;
    
        return $this;
    }
    
    /**
     * Add history
     *
     * @param \UCrm\CoreBundle\Entity\TerritoryHistory $history
     * @return Territory
     */
    public function addHistory(\UCrm\CoreBundle\Entity\TerritoryHistory $history)
    {
        $this->history[] = $history;
    
        return $this;
    }

    /**
     * Remove history
     *
     * @param \UCrm\CoreBundle\Entity\TerritoryHistory $history
     */
    public function removeHistory(\UCrm\CoreBundle\Entity\TerritoryHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Territory
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set checkedOutOn
     *
     * @param \DateTime $checkedOutOn
     * @return Territory
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

    public function hasCheckedOutOn()
    {
        return isset($this->checkedOutOn);
    }

    public function getCheckedOutOnFormatted()
    {
        return $this->hasCheckedOutOn() ?
            $this->getCheckedOutOn()->format('m/d/Y') : "";
    }

    /**
     * Set checkedInOn
     *
     * @param \DateTime $checkedInOn
     * @return Territory
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
     * Set checkedOutTo
     *
     * @param integer $checkedOutTo
     * @return Territory
     */
    public function setCheckedOutTo($checkedOutTo)
    {
        $this->checkedOutTo = $checkedOutTo;
    
        return $this;
    }

    /**
     * Get checkedOutTo
     *
     * @return integer 
     */
    public function getCheckedOutTo()
    {
        return $this->checkedOutTo;
    }

    /**
     * Set user
     *
     * @param \UCrm\CoreBundle\Entity\User $user
     * @return Territory
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

    /**
     * Set code
     *
     * @param string $code
     * @return Territory
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}