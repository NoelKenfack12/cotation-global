<?php

namespace App\Entity\Produit\Parametre;

use App\Repository\Produit\Parametre\OptionFormInputRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionFormInputRepository::class)
 * @ORM\Table("option_form_input")
 */
class OptionFormInput
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Forminput::class, inversedBy="optionFormInputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forminput;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
