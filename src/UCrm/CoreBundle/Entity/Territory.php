<?php

namespace UCrm\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Territory
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Territory
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
     * @var string
     *
     * @ORM\Column(name="coords", type="json_array", nullable=true)
     */
    private $coords = null;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=255, nullable=true)
     */
    private $map = null;

    /**
     * @ORM\OneToMany(targetEntity="TerritoryHistory", mappedBy="territory")
     */
    private $history;


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
     * Get checkoutOutBy
     *
     * @return integer 
     */
    public function getCheckoutOutBy()
    {
        return $this->checkoutOutBy;
    }

    /**
     * Set checkoutOn
     *
     * @param \DateTime $checkoutOn
     * @return Territory
     */
    public function setCheckoutOn($checkoutOn)
    {
        $this->checkoutOn = $checkoutOn;
    
        return $this;
    }

    /**
     * Get checkoutOn
     *
     * @return \DateTime 
     */
    public function getCheckoutOn()
    {
        return $this->checkoutOn;
    }

    /**
     * Set checkinOn
     *
     * @param \DateTime $checkinOn
     * @return Territory
     */
    public function setCheckinOn($checkinOn)
    {
        $this->checkinOn = $checkinOn;
    
        return $this;
    }

    /**
     * Get checkinOn
     *
     * @return \DateTime 
     */
    public function getCheckinOn()
    {
        return $this->checkinOn;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->history = new \Doctrine\Common\Collections\ArrayCollection();
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
}