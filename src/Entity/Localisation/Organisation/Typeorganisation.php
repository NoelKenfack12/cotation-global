<?php

namespace App\Entity\Localisation\Organisation;

use App\Repository\Localisation\Organisation\TypeorganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeorganisationRepository::class)
 * @ORM\Table("typeorganisation")
 */
class Typeorganisation
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=TypeorgServiceorg::class, mappedBy="typeorganisation", orphanRemoval=true)
     */
    private $typeorgServiceorgs;

    /**
     * @ORM\OneToMany(targetEntity=TypeorgOrganisation::class, mappedBy="typeorganisation", orphanRemoval=true)
     */
    private $typeorgOrganisations;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->typeorgServiceorgs = new ArrayCollection();
        $this->typeorgOrganisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, TypeorgServiceorg>
     */
    public function getTypeorgServiceorgs(): Collection
    {
        return $this->typeorgServiceorgs;
    }

    public function addTypeorgServiceorg(TypeorgServiceorg $typeorgServiceorg): self
    {
        if (!$this->typeorgServiceorgs->contains($typeorgServiceorg)) {
            $this->typeorgServiceorgs[] = $typeorgServiceorg;
            $typeorgServiceorg->setTypeorganisation($this);
        }

        return $this;
    }

    public function removeTypeorgServiceorg(TypeorgServiceorg $typeorgServiceorg): self
    {
        if ($this->typeorgServiceorgs->removeElement($typeorgServiceorg)) {
            // set the owning side to null (unless already changed)
            if ($typeorgServiceorg->getTypeorganisation() === $this) {
                $typeorgServiceorg->setTypeorganisation(null);
            }
        }

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
            $typeorgOrganisation->setTypeorganisation($this);
        }

        return $this;
    }

    public function removeTypeorgOrganisation(TypeorgOrganisation $typeorgOrganisation): self
    {
        if ($this->typeorgOrganisations->removeElement($typeorgOrganisation)) {
            // set the owning side to null (unless already changed)
            if ($typeorgOrganisation->getTypeorganisation() === $this) {
                $typeorgOrganisation->setTypeorganisation(null);
            }
        }

        return $this;
    }
}
