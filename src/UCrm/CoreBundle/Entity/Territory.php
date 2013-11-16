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

    const StatusCheckedIn = 5;


    public static $statuses = [
        '',
        'Checked out, but not recorded',
        'Checked out',
        'Never worked',
        'Worked',
        'Checked in'
    ];

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
     * @var integer
     *
     * @ORM\Column(name="checked_out_to", type="integer", nullable=true)
     */
    private $checkedOutTo = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="territory")
     * @ORM\JoinColumn(name="checked_out_to", referencedColumnName="id")
     */
    private $user;

    private $center;

    private $pathString;


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

    public function howLongOut() 
    {
        $status = $this->getStatus();
        if ($status != self::StatusCheckedOutRecorded &&
            $status != self::StatusCheckedOutNotRecorded)
            return "(Error)";

        $interval = $this->getCheckedOutOn()->diff(new \DateTime());
        return $interval ? $interval->format("%d") : "";
    }

    public function center() 
    {
        if (!$this->center) {
            $this->generateMeta();
        }

        return $this->center;
    }

    public function pathString() 
    {
        if (!$this->pathString) {
            $this->generateMeta();
        }

        return $this->pathString;
    }

    private function generateMeta() 
    {
        $coords = json_decode($this->getCoords(), true);

        $latSum = 0.0;
        $lonSum = 0.0;
        $path = "color:0x0000ff|weight:2|fillcolor:0x0000ff";
        $first = null;
        foreach ($coords as $coord) {
            $keys = array_keys($coord);

            $lat = $coord[$keys[0]];
            $lon = $coord[$keys[1]];

            $latSum += $lat;
            $lonSum += $lon;

            $path .= "|{$lat},{$lon}";

            if (!$first) {
                $first = [$lat, $lon];
            }
        }

        $total = count($coords);
        if ($total > 0) {
            $path .= "|{$first[0]},{$first[1]}";
        }

        $this->center = ['lat' => $latSum/$total, 'lon' => $lonSum/$total];
        $this->pathString = $path;
    }
}