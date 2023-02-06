<?php

namespace App\Entity\Localisation\Organisation;

use App\Entity\Localisation\Localite\Ville;
use App\Repository\Localisation\Organisation\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrganisationRepository::class)
 * @ORM\Table("organisation")
 */
class Organisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=TypeorgOrganisation::class, mappedBy="organisation", orphanRemoval=true)
     */
    private $typeorgOrganisations;

    public function __construct()
    {
        $this->typeorgOrganisations = new ArrayCollection();
        $this->date = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

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

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, TypeorgOrganisation>
     */
    public function getTypeorgOrganisations(): Collection
    {
        return $this->typeorgOrganisations;
    }

    public function addTypeorgOrganisation(TypeorgOrganisation $typeorgOrganisation): self
    {
        if (!$this->typeorgOrganisations->contains($typeorgOrganisation)) {
            $this->typeorgOrganisations[] = $typeorgOrganisation;
            $typeorgOrganisation->setOrganisation($this);
        }

        return $this;
    }

    public function removeTypeorgOrganisation(TypeorgOrganisation $typeorgOrganisation): self
    {
        if ($this->typeorgOrganisations->removeElement($typeorgOrganisation)) {
            // set the owning side to null (unless already changed)
            if ($typeorgOrganisation->getOrganisation() === $this) {
                $typeorgOrganisation->setOrganisation(null);
            }
        }

        return $this;
    }
}
