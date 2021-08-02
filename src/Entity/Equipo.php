<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EquipoRepository")
 */
class Equipo
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadModulo", mappedBy="equipo")
     */
    private $propiedadModulos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="equipos")
     */
    private $cliente;

    public function __toString()
    {
        return $this->codigo;
    }
    public function __construct()
    {
        $this->propiedadModulos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

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
            $propiedadModulo->setEquipo($this);
        }

        return $this;
    }

    public function removePropiedadModulo(PropiedadModulo $propiedadModulo): self
    {
        if ($this->propiedadModulos->contains($propiedadModulo)) {
            $this->propiedadModulos->removeElement($propiedadModulo);
            // set the owning side to null (unless already changed)
            if ($propiedadModulo->getEquipo() === $this) {
                $propiedadModulo->setEquipo(null);
            }
        }

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }
}
