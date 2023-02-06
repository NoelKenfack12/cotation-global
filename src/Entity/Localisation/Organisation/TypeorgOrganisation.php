<?php

namespace App\Entity\Localisation\Organisation;

use App\Repository\Localisation\Organisation\TypeorgOrganisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeorgOrganisationRepository::class)
 * @ORM\Table("typeorg_organisation")
 */
class TypeorgOrganisation
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
     * @ORM\ManyToOne(targetEntity=Organisation::class, inversedBy="typeorgOrganisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    /**
     * @ORM\ManyToOne(targetEntity=Typeorganisation::class, inversedBy="typeorgOrganisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeorganisation;

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

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

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
}
