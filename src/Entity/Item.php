<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Item.
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
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
 */
class Item
{
    use SoftDeleteableEntity;
    
    public static $TIPO_ARRAY = array(
        'Texto' => 'texto',
        'Numero' => 'numero',
        'Seleccion multiple' => 'seleccion_multiple',
        'Casilla de verificacion' => 'casilla_de_verificacion',
        'Desplegable' => 'desplegable',
        'Fecha' => 'date',
        'Fecha y Hora' => 'date_time',
        'Hora' => 'time',
        'Foto' => 'foto',
        'Titulo' => 'titulo',
        'Texto en Mayuscula' => 'texto_mayuscula',
        'Equipo' => 'equipo',
    );

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=false)
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Opcion", mappedBy="item" ,cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid
     * @Groups({"read", "readFormularioResultadoExpress"})
     */
    private $opciones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadItem", mappedBy="item")
     */
    private $propiedadItems;

    /**
     * @ORM\Column(type="boolean")
     */
    private $incidencia = false;

    public function __construct()
    {
        $this->opciones = new ArrayCollection();
        $this->propiedadItems = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre.' - '.$this->titulo.' ('.$this->getTipoToString().')';
    }

    public function getTipoToString()
    {
        $ret = array_search($this->tipo, static::$TIPO_ARRAY);

        return $ret;
    }

    public function TipoToString()
    {
        return $this->getTipoToString();
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

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection|Opcion[]
     */
    public function getOpciones(): Collection
    {
        return $this->opciones;
    }

    public function addOpcione(Opcion $opcione): self
    {
        if (!$this->opciones->contains($opcione)) {
            $this->opciones[] = $opcione;
            $opcione->setItem($this);
        }

        return $this;
    }

    public function removeOpcione(Opcion $opcione): self
    {
        if ($this->opciones->contains($opcione)) {
            $this->opciones->removeElement($opcione);
            // set the owning side to null (unless already changed)
            if ($opcione->getItem() === $this) {
                $opcione->setItem(null);
            }
        }

        return $this;
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
            $propiedadItem->setItem($this);
        }

        return $this;
    }

    public function removePropiedadItem(PropiedadItem $propiedadItem): self
    {
        if ($this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems->removeElement($propiedadItem);
            // set the owning side to null (unless already changed)
            if ($propiedadItem->getItem() === $this) {
                $propiedadItem->setItem(null);
            }
        }

        return $this;
    }

    public function obtenerOpcionesConTitulo()
    {
        $ret = [];

        foreach ($this->opciones as $key => $opcion) {
            $ret[$opcion->getId()] = $opcion->getNombre();
        }

        return $ret;
    }

    public function obtenerOpcionesConTituloParaFormulario()
    {
        $ret = [];

        foreach ($this->opciones as $key => $opcion) {
            $ret[$opcion->getNombre()] = $opcion->getId();
        }

        return $ret;
    }

    public function obtenerOpcionesId()
    {
        $ret = [];

        foreach ($this->opciones as $key => $opcion) {
            $ret[] = $opcion->getId();
        }

        return $ret;
    }

    public function formatearSegunItem($valor)
    {
        if ('date' == $this->tipo) {
            $fecha = \DateTime::createFromFormat('d-m-Y', $valor);

            $ret = ($fecha) ? $fecha->format('d/m/Y') : '';
        } elseif ('date_time' == $this->tipo) {
            $fecha = \DateTime::createFromFormat('d-m-Y H:i', $valor);
            $fecha = ($fecha) ? $fecha : \DateTime::createFromFormat('Y-m-d\TH:i', $valor);
            $ret = ($fecha) ? $fecha->format('d/m/Y H:i') : '';
        } elseif ('time' == $this->tipo) {
            $fecha = \DateTime::createFromFormat('H:i', $valor);
            $fecha = ($fecha) ? $fecha : \DateTime::createFromFormat('H:i:s.u', $valor);
            $ret = ($fecha) ? $fecha->format('H:i') : '';
        } else {
            $ret = $valor;
        }

        return  $ret;
    }

    public function obternerValorDeOpcion($opcionId)
    {
        $ret = '';

        if ($this->opciones->count() > 0) {
            foreach ($this->opciones as $key => $opcion) {
                if ($opcion->getId() == $opcionId) {
                    $ret = $opcion->getNombre();
                }
            }
        } else {
            $ret = $this->formatearSegunItem($opcionId);
        }

        return $ret;
    }

    public function getIncidencia(): ?bool
    {
        return $this->incidencia;
    }

    public function setIncidencia(bool $incidencia): self
    {
        $this->incidencia = $incidencia;

        return $this;
    }
}
