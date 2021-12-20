<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Opcion.
 *
 * @ORM\Table(name="opcion")
 * @ORM\Entity(repositoryClass="App\Repository\OpcionRepository")
 * @Vich\Uploadable
 */
class Opcion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Groups({"read","readFormularioResultadoExpress"})
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $imagen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="opciones")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @Vich\UploadableField(mapping="opcion_imagen", fileNameProperty="imagen", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadItem", mappedBy="opcionDepende")
     */
    private $propiedadItems;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $incidencia;

    public function __construct()
    {
        $this->propiedadItems = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return Collection|PropiedadItem[]
     */
    public function getPropiedadItems(): Collection
    {
        return $this->propiedadItems;
    }

    public function addPropiedadItem(PropiedadItem $propiedadItem): self
    {
        if (!$this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems[] = $propiedadItem;
            $propiedadItem->setOpcionDepende($this);
        }

        return $this;
    }

    public function removePropiedadItem(PropiedadItem $propiedadItem): self
    {
        if ($this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems->removeElement($propiedadItem);
            // set the owning side to null (unless already changed)
            if ($propiedadItem->getOpcionDepende() === $this) {
                $propiedadItem->setOpcionDepende(null);
            }
        }

        return $this;
    }

    public function getIncidencia(): ?int
    {
        return $this->incidencia;
    }

    public function setIncidencia(int $incidencia): self
    {
        $this->incidencia = $incidencia;

        return $this;
    }
}
