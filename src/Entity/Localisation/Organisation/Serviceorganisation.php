<?php

namespace App\Entity\Localisation\Organisation;

use App\Entity\Produit\Parametre\Forminput;
use App\Repository\Localisation\Organisation\ServiceorganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceorganisationRepository::class)
 * @ORM\Table("serviceorganisation")
 */
class Serviceorganisation
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Forminput::class, mappedBy="serviceorganisation", orphanRemoval=true)
     */
    private $forminputs;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->forminputs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Forminput>
     */
    public function getForminputs(): Collection
    {
        return $this->forminputs;
    }

    public function addForminput(Forminput $forminput): self
    {
        if (!$this->forminputs->contains($forminput)) {
            $this->forminputs[] = $forminput;
            $forminput->setServiceorganisation($this);
        }

        return $this;
    }

    public function removeForminput(Forminput $forminput): self
    {
        if ($this->forminputs->removeElement($forminput)) {
            // set the owning side to null (unless already changed)
            if ($forminput->getServiceorganisation() === $this) {
                $forminput->setServiceorganisation(null);
            }
        }

        return $this;
    }
}
