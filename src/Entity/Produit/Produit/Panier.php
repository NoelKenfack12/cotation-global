<?php

namespace App\Entity\Produit\Produit;

use App\Entity\Localisation\Organisation\Organisation;
use App\Entity\Localisation\Organisation\Serviceorganisation;
use App\Entity\Localisation\Organisation\Typeorganisation;
use App\Entity\Users\User\Contact;
use App\Repository\Produit\Produit\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 * @ORM\Table("panier")
 */
class Panier
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;

    /**
     * @ORM\OneToMany(targetEntity=PanierInput::class, mappedBy="panier")
     */
    private $panierInputs;

    /**
     * @ORM\Column(type="string", length=255)
     * pending | active | corbeille | brouillon
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Serviceorganisation::class)
     */
    private $serviceorganisation;

    /**
     * @ORM\ManyToOne(targetEntity=Typeorganisation::class)
     */
    private $typeorganisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Organisation::class)
     */
    private $organisation;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class)
     */
    private $contact;

    public function __construct()
    {
        $this->panierInputs = new ArrayCollection();
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, PanierInput>
     */
    public function getPanierInputs(): Collection
    {
        return $this->panierInputs;
    }

    public function addPanierInput(PanierInput $panierInput): self
    {
        if (!$this->panierInputs->contains($panierInput)) {
            $this->panierInputs[] = $panierInput;
            $panierInput->setPanier($this);
        }

        return $this;
    }

    public function removePanierInput(PanierInput $panierInput): self
    {
        if ($this->panierInputs->removeElement($panierInput)) {
            // set the owning side to null (unless already changed)
            if ($panierInput->getPanier() === $this) {
                $panierInput->setPanier(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getServiceorganisation(): ?Serviceorganisation
    {
        return $this->serviceorganisation;
    }

    public function setServiceorganisation(?Serviceorganisation $serviceorganisation): self
    {
        $this->serviceorganisation = $serviceorganisation;

        return $this;
    }

    public function getTypeorganisation(): ?Typeorganisation
    {
        return $this->typeorganisation;
    }

    public function setTypeorganisation(?Typeorganisation $typeorganisation): self
    {
        $this->typeorganisation = $typeorganisation;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getClientNumber(): ?string
    {
        return $this->clientNumber;
    }

    public function setClientNumber(?string $clientNumber): self
    {
        $this->clientNumber = $clientNumber;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
