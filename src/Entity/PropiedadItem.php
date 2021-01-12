<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

/**
 * PropiedadItem.
 *
 * @ORM\Table(name="propiedad_item")
 * @ORM\Entity(repositoryClass="App\Repository\PropiedadItemRepository")
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "denormalization_context"={"groups"={"write"}}
 *     },
 *  collectionOperations = {
 *     "get"={"method"="GET"}
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  }
 * )
 * @AppAssert\PropiedadItemConstraint
 */
class PropiedadItem
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
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="ancho", type="decimal", precision=10, scale=0)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $ancho;

    /**
     * @var bool
     *
     * @ORM\Column(name="requerido", type="boolean", nullable=false, options={"default": 0})
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $requerido = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false, options={"default" : 1})
     */
    private $activo = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Opcion")
     * @ORM\JoinColumn(name="opcion_id", referencedColumnName="id")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $opcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modulo", inversedBy="propiedadItems")
     * @ORM\JoinColumn(name="modulo_id", referencedColumnName="id")
     */
    private $modulo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="propiedadItems")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read","readFormularioResultadoExpress"})
     * @Assert\NotBlank()
     */
    private $item;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCollection", type="boolean", nullable=false, options={"default": 0})
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $isCollection = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resultado", mappedBy="propiedadItem")
     */
    private $resultados;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropiedadItem", inversedBy="propiedadItems")
     */
    private $dependePropiedadItem;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadItem", mappedBy="dependePropiedadItem")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $propiedadItems;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Opcion", inversedBy="propiedadItems")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $opcionDepende;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $cantidadMinima;

    public function __construct()
    {
        $this->resultados = new ArrayCollection();
        $this->propiedadItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->item;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getAncho()
    {
        return $this->ancho;
    }

    public function setAncho($ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getRequerido(): ?bool
    {
        return $this->requerido;
    }

    public function setRequerido(bool $requerido): self
    {
        $this->requerido = $requerido;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getIsCollection(): ?bool
    {
        return $this->isCollection;
    }

    public function setIsCollection(bool $isCollection): self
    {
        $this->isCollection = $isCollection;

        return $this;
    }

    public function getOpcion(): ?Opcion
    {
        return $this->opcion;
    }

    public function setOpcion(?Opcion $opcion): self
    {
        $this->opcion = $opcion;

        return $this;
    }

    public function getModulo(): ?Modulo
    {
        return $this->modulo;
    }

    public function setModulo(?Modulo $modulo): self
    {
        $this->modulo = $modulo;

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
     * @return Collection|Resultado[]
     */
    public function getResultados(): Collection
    {
        return $this->resultados;
    }

    public function addResultado(Resultado $resultado): self
    {
        if (!$this->resultados->contains($resultado)) {
            $this->resultados[] = $resultado;
            $resultado->setPropiedadItem($this);
        }

        return $this;
    }

    public function removeResultado(Resultado $resultado): self
    {
        if ($this->resultados->contains($resultado)) {
            $this->resultados->removeElement($resultado);
            // set the owning side to null (unless already changed)
            if ($resultado->getPropiedadItem() === $this) {
                $resultado->setPropiedadItem(null);
            }
        }

        return $this;
    }

    public function isCollection()
    {
        return ($this->isCollection) ? 'Si' : 'No';
    }

    public function getDependePropiedadItem(): ?self
    {
        return $this->dependePropiedadItem;
    }

    public function setDependePropiedadItem(?self $dependePropiedadItem): self
    {
        $this->dependePropiedadItem = $dependePropiedadItem;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPropiedadItems(): Collection
    {
        return $this->propiedadItems;
    }

    public function addPropiedadItem(self $propiedadItem): self
    {
        if (!$this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems[] = $propiedadItem;
            $propiedadItem->setDependePropiedadItem($this);
        }

        return $this;
    }

    public function removePropiedadItem(self $propiedadItem): self
    {
        if ($this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems->removeElement($propiedadItem);
            // set the owning side to null (unless already changed)
            if ($propiedadItem->getDependePropiedadItem() === $this) {
                $propiedadItem->setDependePropiedadItem(null);
            }
        }

        return $this;
    }

    public function getOpcionDepende(): ?Opcion
    {
        return $this->opcionDepende;
    }

    public function setOpcionDepende(?Opcion $opcionDepende): self
    {
        $this->opcionDepende = $opcionDepende;

        return $this;
    }

    public function getCantidadMinima(): ?int
    {
        return $this->cantidadMinima;
    }

    public function setCantidadMinima(?int $cantidadMinima): self
    {
        $this->cantidadMinima = $cantidadMinima;

        return $this;
    }
}
