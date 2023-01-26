<?php

namespace App\Entity\Users\User;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Validatortext\Email;
use App\Validator\Validatortext\Taillemin;
use App\Validator\Validatortext\Taillemax;
use App\Validator\Validatortext\Password;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\Users\User\UserRepository;

/**
 * User
 *
 * @ORM\Table("user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="username", message="Ce  mail existe déjà.")
 ** @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
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
     * @ORM\Column(name="nom", type="string", length=255)
    */
    private $nom;
	
	/**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
    */
    private $prenom;
	
	/**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Email()
    */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
	
	/**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;
	
	/**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateins", type="datetime")
     */
    private $dateins;
    
	private $servicetext;

	
	public function __construct(GeneralServicetext $service)
    {
        $this->servicetext = $service;
        $this->dateins = new \Datetime();
        $this->roles = array('ROLE_USER');
    }
	
	public function getServicetext()
    {
        return $this->servicetext;
    }
	
	public function setServicetext(GeneralServicetext $service)
    {
        $this->servicetext = $service;
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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dateins
     *
     * @param \DateTime $dateins
     * @return User
     */
    public function setDateins($dateins)
    {
        $this->dateins = $dateins;

        return $this;
    }

    /**
     * Get dateins
     *
     * @return \DateTime 
     */
    public function getDateins()
    {
        return $this->dateins;
    }

	
	/**
     * @ORM\PrePersist()
     */
    public function premajuscule()
	{
        $text1 = $this->servicetext->retireAccent($this->nom);
        $text1 = strtolower($text1);
        $this->nom = ucwords($text1);
	}
	
	 /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }
	
	public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }
	public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }
	
	/**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }
	
	/**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
	
	public function eraseCredentials()
    {
    
    }
	
	public function name($tail)
    {
        $allname = $this->nom.' '.$this->prenom;
        if(strlen($allname) <= $tail)
        {
            return $allname;
        }else{
            $text = wordwrap($allname,$tail,'~',true);
            $tab = explode('~',$text);
            $text = $tab[0];
            return $text.'...';
        }
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }


}
