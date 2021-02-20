<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

//use Sistema\FormularioBundle\Validator\Constraints as FormularioAssert;

/**
 * Formulario.
 *
 * @ORM\Table(name="formulario")
 * @ORM\Entity(repositoryClass="App\Repository\FormularioRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @UniqueEntity("nombre")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"read", "readFormularioResultadoExpress"}},
 *     "denormalization_context"={"groups"={"write"}}
 *     },
 *  collectionOperations = {
 *     "get"={"method"="GET"},
 *     "express" = {
 *             "method" =  "GET",
 *             "path" = "/formularios/by/express",
 *             "controller" = "App\Action\FormularioByExpress",
 *             "normalization_context"={"groups"={"readList","readFormularioResultadoExpress"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        },
 *     "cm" = {
 *             "method" =  "GET",
 *             "path" = "/formularios/by/cm",
 *             "controller" = "App\Action\FormularioByCM",
 *             "normalization_context"={"groups"={"readList","readFormularioResultadoExpress"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        }
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  }
 * )
 */
class Formulario
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read","readList","readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Groups({"read","readList","readFormularioResultadoExpress"})
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     * @Groups({"read","readList","readFormularioResultadoExpress"})
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadModulo", mappedBy="formulario",cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"pagina" = "ASC","orden" = "ASC"})
     * @Assert\Valid
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $propiedadModulos;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=false)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="formulario")
     */
    private $ordenTrabajo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormularioResultadoExpress", mappedBy="formulario")
     */
    private $formularioExpress;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $express;

     /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $compraMateriales;

    public function __construct()
    {
        $this->propiedadModulos = new ArrayCollection();
        $this->usuarioFormularios = new ArrayCollection();
        $this->version = 0;
        $this->ordenTrabajo = new ArrayCollection();
        $this->formularioExpress = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * @ORM\PrePersist
     */
    public function setPrePersistVersionIncrement()
    {
        $this->updateVersionForm();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setPreUpdateVersionIncrement()
    {
        $this->updateVersionForm();
    }

    private function updateVersionForm()
    {
        ++$this->version;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|PropiedadModulo[]
     */
    public function getPropiedadModulos(): Collection
    {
        return $this->propiedadModulos;
    }

    public function addPropiedadModulo(PropiedadModulo $propiedadModulo): self
    {
        if (!$this->propiedadModulos->contains($propiedadModulo)) {
            $this->propiedadModulos[] = $propiedadModulo;
            $propiedadModulo->setFormulario($this);
        }

        return $this;
    }

    public function removePropiedadModulo(PropiedadModulo $propiedadModulo): self
    {
        if ($this->propiedadModulos->contains($propiedadModulo)) {
            $this->propiedadModulos->removeElement($propiedadModulo);
            // set the owning side to null (unless already changed)
            if ($propiedadModulo->getFormulario() === $this) {
                $propiedadModulo->setFormulario(null);
            }
        }

        return $this;
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

    /**
     * @return Collection|OrdenTrabajo[]
     */
    public function getOrdenTrabajo(): Collection
    {
        return $this->ordenTrabajo;
    }

    public function addOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if (!$this->ordenTrabajo->contains($ordenTrabajo)) {
            $this->ordenTrabajo[] = $ordenTrabajo;
            $ordenTrabajo->setFormulario($this);
        }

        return $this;
    }

    public function removeOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if ($this->ordenTrabajo->contains($ordenTrabajo)) {
            $this->ordenTrabajo->removeElement($ordenTrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenTrabajo->getFormulario() === $this) {
                $ordenTrabajo->setFormulario(null);
            }
        }

        return $this;
    }

    public function getExpress(): ?bool
    {
        return $this->express;
    }

    public function setExpress(?bool $express): self
    {
        $this->express = $express;

        return $this;
    }

    public function getCompraMateriales(): ?bool
    {
        return $this->compraMateriales;
    }

    public function setCompraMateriales(?bool $compraMateriales): self
    {
        $this->compraMateriales = $compraMateriales;

        return $this;
    }

    /**
     * @return Collection|FormularioResultadoExpress[]
     */
    public function getFormularioExpress(): Collection
    {
        return $this->formularioExpress;
    }

    public function addFormularioExpress(FormularioResultadoExpress $formularioExpress): self
    {
        if (!$this->formularioExpress->contains($formularioExpress)) {
            $this->formularioExpress[] = $formularioExpress;
            $formularioExpress->setFormulario($this);
        }

        return $this;
    }

    public function removeFormularioExpress(FormularioResultadoExpress $formularioExpress): self
    {
        if ($this->formularioExpress->contains($formularioExpress)) {
            $this->formularioExpress->removeElement($formularioExpress);
            // set the owning side to null (unless already changed)
            if ($formularioExpress->getFormulario() === $this) {
                $formularioExpress->setFormulario(null);
            }
        }

        return $this;
    }
}
