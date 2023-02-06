<?php

namespace App\Entity\Produit\Service;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Validatortext\Taillemin;
use App\Validator\Validatortext\Taillemax;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Validatorfile\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\Produit\Service\PaysRepository;
use App\Entity\Users\User\User;
use App\Entity\Users\User\Contacts;
use App\Entity\Produit\Service\Drapeau;
use App\Entity\Produit\Service\Continent;
use Doctrine\Common\Collections\Collection;

/**
 * Pays
 *
 * @ORM\Table("pays")
 * @ORM\Entity(repositoryClass=PaysRepository::class)
 * @UniqueEntity(fields="nom", message="Ce pays est déjà enregistré.")
 * @UniqueEntity(fields="code", message="Ce code existe déjà.")
 ** @ORM\HasLifecycleCallbacks
 */
 
class Pays
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
     * @ORM\Column(name="nom", type="string", length=255,unique=true)
     *@Taillemin(valeur=4,message="Au moins 4 caractères")
     *@Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="citoyen", type="string", length=255,nullable=true)
     * @Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $citoyen;
	
	/**
     * @var string
     *
     * @ORM\Column(name="citoyenne", type="string", length=255,nullable=true)
     * @Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $citoyenne;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255,unique=true,nullable=true)
     */
    private $code;
	
	/**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=255,unique=true,nullable=true)
     */
    private $domaine;
	
	/**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="pays")
     */
    private $users;
	
	/**
     * @ORM\OneToOne(targetEntity=Drapeau::class,  cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
    */
	private $drapeau;
	
	/**
	* @ORM\ManyToOne(targetEntity=Continent::class, inversedBy="pays")
	* @ORM\JoinColumn(nullable=false)
	*/
    private $continent;
	
	// variable du service de normalisation des noms de fichier
	private $servicetext;
	
	
	public function __construct(GeneralServicetext $service)
	{
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Pays
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
     * Set citoyen
     *
     * @param string $citoyen
     * @return Pays
     */
    public function setCitoyen($citoyen)
    {
        $this->citoyen = $citoyen;

        return $this;
    }

    /**
     * Get citoyen
     *
     * @return string 
     */
    public function getCitoyen()
    {
        return $this->citoyen;
    }

	 /**
     * Set code
     *
     * @param string $citoyen
     * @return Pays
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
	
	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function premajuscule()
	{
        $this->nom =  ucfirst($this->nom);
        $this->citoyen =  ucfirst($this->citoyen);
        $this->citoyenne = ucfirst($this->citoyenne);
	}


    /**
     * Set citoyenne
     *
     * @param string $citoyenne
     * @return Pays
     */
    public function setCitoyenne($citoyenne)
    {
        $this->citoyenne = $citoyenne;

        return $this;
    }

    /**
     * Get citoyenne
     *
     * @return string 
     */
    public function getCitoyenne()
    {
        return $this->citoyenne;
    }
	
	public function name($tail)
	{
		if(strlen($this->nom) <= $tail)
		{
		return $this->nom;
		}else{
		$text = wordwrap($this->nom,$tail,'~',true);
		$tab = explode('~',$text);
		$text = $tab[0];
		return $text.'...';
		}
	}

    /**
     * Add users
     * @return Pays
     */
    public function addUser(User $users): self
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users 
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    /**
     * Set domaine
     *
     * @param string $domaine
     * @return Pays
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return string 
     */
    public function getDomaine()
    {
        return $this->domaine;
    }
	
	public function setServicetext(GeneralServicetext $service)
	{
	    $this->servicetext = $service;
	}
	public function getServicetext()
	{
	    return $this->servicetext;
	}

    /**
     * Set drapeau
     * @return Pays
     */
    public function setDrapeau(Drapeau $drapeau = null): self
    {
        $this->drapeau = $drapeau;

        return $this;
    }

    /**
     * Get drapeau
     */
    public function getDrapeau(): ?Drapeau
    {
        return $this->drapeau;
    }

    /**
     * Set continent
     * @return Pays
     */
    public function setContinent(Continent $continent): self
    {
        $this->continent = $continent;
		$continent->addPay($this);

        return $this;
    }

    /**
     * Get continent
     */
    public function getContinent(): ?Continent
    {
        return $this->continent;
    }
}
