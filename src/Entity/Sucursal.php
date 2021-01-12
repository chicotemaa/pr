<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SucursalRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ApiResource
 * @Vich\Uploadable
 */
class Sucursal
{
    use SoftDeleteableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="Pais")
     */
    private $pais;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textoCabecera;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textoPie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageCabecera = '';

    /**
     * @Vich\UploadableField(mapping="sucursal_cabecera_imagen", fileNameProperty="imageCabecera")
     *
     * @var File
     */
    private $imageCabeceraFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imagePie = '';

    /**
     * @Vich\UploadableField(mapping="sucursal_pie_imagen", fileNameProperty="imagePie")
     *
     * @var File
     */
    private $imagePieFile;

    public function __toString()
    {
        return ($this->nombre) ? $this->nombre : '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageCabeceraFile(File $image = null)
    {
        $this->imageCabeceraFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageCabeceraFile()
    {
        return $this->imageCabeceraFile;
    }

    public function setImageCabecera($imageCabecera)
    {
        $this->imageCabecera = $imageCabecera;
    }

    public function getImageCabecera()
    {
        return $this->imageCabecera;
    }

    public function setImagePieFile(File $image = null)
    {
        $this->imagePieFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImagePieFile()
    {
        return $this->imagePieFile;
    }

    public function setImagePie($imagePie)
    {
        $this->imagePie = $imagePie;
    }

    public function getImagePie()
    {
        return $this->imagePie;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getTextoCabecera(): ?string
    {
        return $this->textoCabecera;
    }

    public function setTextoCabecera(?string $textoCabecera): self
    {
        $this->textoCabecera = $textoCabecera;

        return $this;
    }

    public function getTextoPie(): ?string
    {
        return $this->textoPie;
    }

    public function setTextoPie(?string $textoPie): self
    {
        $this->textoPie = $textoPie;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
