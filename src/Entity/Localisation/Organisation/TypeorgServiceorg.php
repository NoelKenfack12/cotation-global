<?php

namespace App\Entity\Localisation\Organisation;

use App\Repository\Localisation\Organisation\TypeorgServiceorgRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeorgServiceorgRepository::class)
 * @ORM\Table("typeorg_serviceorg")
 */
class TypeorgServiceorg
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
     * @ORM\ManyToOne(targetEntity=Serviceorganisation::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $serviceorganisation;

    /**
     * @ORM\ManyToOne(targetEntity=Typeorganisation::class, inversedBy="typeorgServiceorgs")
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
}
