<?php

namespace UCrm\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Address
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
     * @ORM\Column(name="street_number", type="string", length=255)
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="street_name", type="string", length=255)
     */
    private $streetName;

    /**
     * @var string
     *
     * @ORM\Column(name="neighborhood", type="string", length=255)
     */
    private $neighborhood;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255)
     */
    private $zip;


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
     * Set streetNumber
     *
     * @param string $streetNumber
     * @return Address
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    
        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string 
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     * @return Address
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;
    
        return $this;
    }

    /**
     * Get streetName
     *
     * @return string 
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Set Neighborhood
     *
     * @param string $neighborhood
     * @return Address
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;
    
        return $this;
    }

    /**
     * Get Neighborhood
     *
     * @return string 
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set City
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * Set zip
     *
     * @param string $zip
     * @return Address
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }
}