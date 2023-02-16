<?php

namespace App\Entity\Produit\Produit;

use App\Repository\Produit\Produit\ProduitpanierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitpanierRepository::class)
 */
class Produitpanier
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
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taxe;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitOrganisation::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitOrganisation;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="produitpaniers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    public function __construct()
	{
        $this->date = new \Datetime();
        $this->taxe = 0;
        $this->quantite = 1;
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

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(?float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getProduitOrganisation(): ?ProduitOrganisation
    {
        return $this->produitOrganisation;
    }

    public function setProduitOrganisation(?ProduitOrganisation $produitOrganisation): self
    {
        $this->produitOrganisation = $produitOrganisation;

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
}
