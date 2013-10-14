<?php

namespace UCrm\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password_hash", type="string", length=255)
     */
    private $passwordHash;

    /**
     * @var string
     *
     * @ORM\Column(name="password_salt", type="string", length=255)
     */
    private $passwordSalt;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="permissions", type="integer")
     */
    private $permissions;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="last_login_at", type="datetime")
     */
    private $lastLoginAt;

    /**
     * @ORM\OneToMany(targetEntity="Client", mappedBy="user")
     */
    private $calls;

    /**
     * @ORM\OneToMany(targetEntity="Territory", mappedBy="user")
     */
    private $territory;

    private $password;



    public function __construct()
    {
        $this->calls = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {

        $this->password = $password;
        $this->setPasswordHash($this->crypto($password));
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function crypto($raw) 
    {
        return crypt($raw, "$1$" . $this->getPasswordSalt() . "$");
    }

    /**
     * Set passwordHash
     *
     * @param string $passwordHash
     * @return User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    
        return $this;
    }

    /**
     * Get passwordHash
     *
     * @return string 
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set passwordSalt
     *
     * @param string $passwordSalt
     * @return User
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    
        return $this;
    }

    /**
     * Get passwordSalt
     *
     * @return string 
     */
    public function getPasswordSalt()
    {
        if (!$this->passwordSalt) {
            $this->setPasswordSalt(substr(md5(time()), 0, 8));
        }

        return $this->passwordSalt;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * Set permissions
     *
     * @param integer $permissions
     * @return User
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    
        return $this;
    }

    /**
     * Get permissions
     *
     * @return integer 
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setLastLoginAt($lastLoginAt) {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    public function getLastLoginAt() {
        return $this->lastLoginAt;
    }

    public function isPasswordMatch($password) 
    {
        return $this->crypto($password) === $this->getPasswordHash();
    }

    public function getHash() 
    {
        return md5($this->getEmail() . "|" . $this->getPasswordHash() . "|" . $this->getLastLoginAt()->getTimestamp());
    }

    public function verifyHash($hash) 
    {   
        return $hash === $this->getHash();
    }

    /**
     * Add calls
     *
     * @param \UCrm\CoreBundle\Entity\Client $calls
     * @return User
     */
    public function addCall(\UCrm\CoreBundle\Entity\Client $calls)
    {
        $this->calls[] = $calls;
    
        return $this;
    }

    /**
     * Remove calls
     *
     * @param \UCrm\CoreBundle\Entity\Client $calls
     */
    public function removeCall(\UCrm\CoreBundle\Entity\Client $calls)
    {
        $this->calls->removeElement($calls);
    }

    /**
     * Get calls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Add territoryHistory
     *
     * @param \UCrm\CoreBundle\Entity\TerritoryHistory $territoryHistory
     * @return User
     */
    public function addTerritoryHistory(\UCrm\CoreBundle\Entity\TerritoryHistory $territoryHistory)
    {
        $this->territoryHistory[] = $territoryHistory;
    
        return $this;
    }

    /**
     * Remove territoryHistory
     *
     * @param \UCrm\CoreBundle\Entity\TerritoryHistory $territoryHistory
     */
    public function removeTerritoryHistory(\UCrm\CoreBundle\Entity\TerritoryHistory $territoryHistory)
    {
        $this->territoryHistory->removeElement($territoryHistory);
    }

    /**
     * Get territoryHistory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTerritoryHistory()
    {
        return $this->territoryHistory;
    }
}