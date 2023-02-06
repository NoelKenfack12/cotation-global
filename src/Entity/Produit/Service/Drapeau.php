<?php

namespace App\Entity\Produit\Service;

use Doctrine\ORM\Mapping as ORM;
use App\Service\Servicetext\GeneralServicetext;
use App\Validator\Validatorfile\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\Produit\Service\DrapeauRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Drapeau
 *
 * @ORM\Table("drapeau")
 * @ORM\Entity(repositoryClass=DrapeauRepository::class)
 * @Vich\Uploadable
 */
class Drapeau
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $contentUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath;

     /**
     * @Vich\UploadableField(mapping="filedrapeau", fileNameProperty="filePath", size="imageSize", mimeType="mimeType", originalName="originalName")
     * @var File|null
     */
    public ?File $file = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $originalName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;
	
	// variable du service de normalisation des noms de fichier
	private $servicefile;
	
	public function __construct()
	{
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
	//permet la récupération du nom du fichier temporaire

    public function getTempFilename()
    {
    	return $this->tempFilename;
    }
	//permet de modifier le contenu de la variable tempFilename
    public function setTempFilename($temp)
	{
		$this->tempFilename=$temp;
	}
	
	public function setServicefile( GeneralServicetext $service)
	{
		$this->servicefile = $service;
	}
	public function getServicefile()
	{
		return $this->servicefile;
	}
	

	public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getImageSize(): ?string
    {
        return $this->imageSize;
    }

    public function setImageSize(?string $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}
