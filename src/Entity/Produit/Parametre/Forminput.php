<?php

namespace App\Entity\Produit\Parametre;

use App\Entity\Localisation\Organisation\Serviceorganisation;
use App\Repository\Produit\Parametre\ForminputRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForminputRepository::class)
 * @ORM\Table("forminput")
 */
class Forminput
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * number | select | text | textarea
     */
    private $typeInput;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @ORM\ManyToOne(targetEntity=Serviceorganisation::class, inversedBy="forminputs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serviceorganisation;

    /**
     * @ORM\OneToMany(targetEntity=OptionFormInput::class, mappedBy="forminput", orphanRemoval=true)
     */
    private $optionFormInputs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $required;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->optionFormInputs = new ArrayCollection();
        $this->required = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getTypeInput(): ?string
    {
        return $this->typeInput;
    }

    public function setTypeInput(string $typeInput): self
    {
        $this->typeInput = $typeInput;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(?string $placeholder): self
    {
        $this->placeholder = $placeholder;

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

    /**
     * @return Collection<int, OptionFormInput>
     */
    public function getOptionFormInputs(): Collection
    {
        return $this->optionFormInputs;
    }

    public function addOptionFormInput(OptionFormInput $optionFormInput): self
    {
        if (!$this->optionFormInputs->contains($optionFormInput)) {
            $this->optionFormInputs[] = $optionFormInput;
            $optionFormInput->setForminput($this);
        }

        return $this;
    }

    public function removeOptionFormInput(OptionFormInput $optionFormInput): self
    {
        if ($this->optionFormInputs->removeElement($optionFormInput)) {
            // set the owning side to null (unless already changed)
            if ($optionFormInput->getForminput() === $this) {
                $optionFormInput->setForminput(null);
            }
        }

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

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }
}
