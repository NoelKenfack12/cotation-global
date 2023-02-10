<?php

namespace App\Entity\Produit\Service;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Validatortext\Taillemin;
use App\Validator\Validatortext\Taillemax;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Service\Servicetext\GeneralServicetext;
use App\Repository\Produit\Service\ContinentRepository;
use App\Entity\Produit\Service\Pays;
use Doctrine\Common\Collections\Collection;

/**
 * Continent
 *
 * @ORM\Table("continent")
 * @ORM\Entity(repositoryClass=ContinentRepository::class)
 * @UniqueEntity(fields="nom", message="Ce continent existe déjà.")
 * @UniqueEntity(fields="citoyen", message="Ce continent existe déjà.")
 ** @ORM\HasLifecycleCallbacks
 */
class Continent
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
     * @ORM\Column(name="nom", type="string", length=100,unique=true)
     * @Taillemin(valeur=4, message="Au moins 4 caractères")
     * @Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $nom;
	
	/**
     * @var string
     *
     * @ORM\Column(name="citoyen", type="string", length=100,unique=true, nullable=true)
     * @Taillemin(valeur=4, message="Au moins 4 caractères")
     * @Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $citoyen;
	
	/**
     * @var string
     *
     * @ORM\Column(name="citoyenne", type="string", length=100,unique=true, nullable=true)
     * @Taillemin(valeur=4, message="Au moins 4 caractères")
     * @Taillemax(valeur=60, message="Au plus 60 caractères")
     */
    private $citoyenne;
	
	/**
     * @ORM\OneToMany(targetEntity=Pays::class, mappedBy="continent")
     */
    private $pays;
	
	private $serviceaccent;
	
	public function __construct(GeneralServicetext $service)
	{
        $this->serviceaccent = $service ;
        $this->pays = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getServiceaccent()
	{
	    return $this->serviceaccent;
	}

	public function setServiceaccent(GeneralServicetext $service)
	{
	    $this->serviceaccent = $service;
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
     * Set src
     *
     * @param string $src
     * @return Imgslide
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Imgslide
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }
	
    /**
     * Set nom
     *
     * @param string $nom
     * @return Continent
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
     * Get pays 
     */
    public function getPays(): ?Collection
    {
        return $this->pays;
    }


    /**
     * Set citoyen
     *
     * @param string $citoyen
     * @return Continent
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
     * Set citoyenne
     *
     * @param string $citoyenne
     * @return Continent
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

    /**
     * Add pays
     * @return Continent
    */
    public function addPay(Pays $pays): self
    {
        $this->pays[] = $pays;

        return $this;
    }

    /**
     * Remove pays
     */
    public function removePay(Pays $pays)
    {
        $this->pays->removeElement($pays);
    }
}
