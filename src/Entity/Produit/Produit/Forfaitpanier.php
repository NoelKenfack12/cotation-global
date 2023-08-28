<?php

namespace App\Entity\Produit\Produit;

use App\Repository\Produit\Produit\ForfaitpanierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForfaitpanierRepository::class)
 * @ORM\Table("forfaitpanier")
 */
class Forfaitpanier
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
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="forfaitpaniers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $panier;

    /**
     * @ORM\ManyToOne(targetEntity=Typeproduit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeproduit;

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

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getTypeproduit(): ?Typeproduit
    {
        return $this->typeproduit;
    }

    public function setTypeproduit(?Typeproduit $typeproduit): self
    {
        $this->typeproduit = $typeproduit;

        return $this;
    }
}
