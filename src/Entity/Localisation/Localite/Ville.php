<?php

namespace App\Entity\Localisation\Localite;

use App\Repository\Localisation\Localite\VilleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit\Service\Pays;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 * @ORM\Table("ville")
 */
class Ville
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
	* @ORM\ManyToOne(targetEntity=Pays::class)
	* @ORM\JoinColumn(nullable=true)
	*/
    private $pays;

    public function __construct()
	{
        $this->date = new \Datetime();
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

    /**
     * Set continent
     * @return Ville
     */
    public function setPays(Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     */
    public function getPays(): ?Pays
    {
        return $this->pays;
    }
}
