<?php

namespace RAAFPAGE\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * RAAFPAGE\UserBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=40, unique=true)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=60, unique=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=60, unique=true)
     */
    private $lastName;

    /**
     * @ORM\Column(name="mobile_name", type="string", length=60, unique=true)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \Datetime
     * @ORM\Column(name="activation_request_sent", type="datetime")
     */
    private $activationEmailSent;

    /**
     * @var \Datetime
     * @ORM\Column(name="activation_time", type="datetime")
     */
    private $activationTime;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

//    /**
//     * @ORM\ManyToMany(targetEntity="Role")
//     * @ORM\JoinTable(name="users_roles")
//     */
//    protected $roles;

    public function __construct()
    {
        $this->isActive = false;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = new ArrayCollection();
    }

    public function getRoles()
    {
        //return $this->roles->toArray();

        //todo replace this with loading ROLE from database
//        if ($this->username == 'admin') {
//            return array('ROLE_ADMIN');
//        } else {
//            return array('ROLE_USER');
//        }
        return array($this->getRole());
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param integer $mobileNumber
     * @return User
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool    true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool    true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool    true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool    true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    public function setActive($active = false)
    {
        $this->isActive = $active;
    }

    /**
     * @return \Datetime
     */
    public function getActivationEmailSent()
    {
        return $this->activationEmailSent;
    }

    /**
     * @param \Datetime $activationEmailSent
     */
    public function setActivationEmailSent($activationEmailSent)
    {
        $this->activationEmailSent = $activationEmailSent;
    }

    /**
     * @return \Datetime
     */
    public function getActivationTime()
    {
        return $this->activationTime;
    }

    /**
     * @param \Datetime $activationTime
     */
    public function setActivationTime($activationTime)
    {
        $this->activationTime = $activationTime;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        /*
         * ! Don't serialize $roles field !
         */
        return \serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->salt,
            $this->password,
            $this->isActive
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->salt,
            $this->password,
            $this->isActive
            ) = \unserialize($serialized);
    }
}
