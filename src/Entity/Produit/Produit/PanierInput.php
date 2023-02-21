<?php

namespace App\Entity\Produit\Produit;

use App\Entity\Produit\Parametre\Forminput;
use App\Repository\Produit\Produit\PanierInputRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierInputRepository::class)
 * @ORM\Table("panier_input")
 */
class PanierInput
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Forminput::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $forminput;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="panierInputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    /**
     * @ORM\Column(type="text")
    */
    private $valeur;

    public function __construct()
    {
        $this->date = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getForminput(): ?Forminput
    {
        return $this->forminput;
    }

    public function setForminput(?Forminput $forminput): self
    {
        $this->forminput = $forminput;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function name($tail)
    {
        $allname = $this->valeur;
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
}
