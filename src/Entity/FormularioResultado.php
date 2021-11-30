<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Validator\Constraints as AppAssert;

/**
 * FormularioResultado.
 *
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"readFormularioResultado"}},
 *     "denormalization_context"={"groups"={"writeFormularioResultado","writePost"}}
 *     },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  }
 * )
 * @ORM\Table(name="formulario_resultado")
 * @ORM\Entity(repositoryClass="App\Repository\FormularioResultadoRepository")
 */
class FormularioResultado
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"readFormularioResultado", "writePost"})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     * @Groups({"readFormularioResultado"})
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resultado", mappedBy="formularioResultado",cascade={"all"}, orphanRemoval=true)
     * @Groups({"writeFormularioResultado","readFormularioResultado","writePost"})
     * @ORM\OrderBy({"id" = "ASC", "indiceModulo" = "ASC", "indiceItem" = "ASC"})
     */
    private $resultados;

    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=255)
     * @Groups({"writeFormularioResultado","readFormularioResultado"})
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=255)
     * @Groups({"writeFormularioResultado","readFormularioResultado"})
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=255, nullable=true)
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"readFormularioResultado"})
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="indetificacion", type="string", length=255, nullable=true)
     * @Groups({"readFormularioResultado"})
     */
    private $indetificacion;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrdenTrabajo", mappedBy="formularioResultado", cascade={"persist", "remove"})
     * @Groups({"writeFormularioResultado"})
     */
    private $ordenTrabajo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado","readFormularioResultado"})
     */
    private $minutosTrabajado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado","readFormularioResultado"})
     */
    private $minutosReales;

    public function __construct()
    {
        $this->resultados = new ArrayCollection();
    }
    public function __toString()
    {
        return 'Formulario: '.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLatitud(): ?string
    {
        return $this->latitud;
    }

    public function setLatitud(string $latitud): self
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?string
    {
        return $this->longitud;
    }

    public function setLongitud(string $longitud): self
    {
        $this->longitud = $longitud;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(?string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(?string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(?string $pais): self
    {
        $this->pais = $pais;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIndetificacion(): ?string
    {
        return $this->indetificacion;
    }

    public function setIndetificacion(?string $indetificacion): self
    {
        $this->indetificacion = $indetificacion;

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
            $resultado->setFormularioResultado($this);
        }

        return $this;
    }

    public function removeResultado(Resultado $resultado): self
    {
        if ($this->resultados->contains($resultado)) {
            $this->resultados->removeElement($resultado);
            // set the owning side to null (unless already changed)
            if ($resultado->getFormularioResultado() === $this) {
                $resultado->setFormularioResultado(null);
            }
        }

        return $this;
    }

    public function setearIdentificacion()
    {
        //Numero + id formulario + id de cliente + id del auditor + created
        $user = $this->usuarioFormulario->getUser()->getId();
        $auditor = ($this->usuarioFormulario->getUser()->getUser()) ? $this->usuarioFormulario->getUser()->getUser()->getId() : '0';
        $fechaActual = new \DateTime();
        $ret = $this->numero.'.'.$this->usuarioFormulario->getFormulario()->getId().'.'.$user.'.'.$auditor.'.'.$fechaActual->format('d.m.y.H.i');

        $this->setIndetificacion($ret);
    }

    public function lugarCompleto()
    {
        $ret = $this->direccion.', ';
        $ret .= $this->ciudad.', ';
        $ret .= (empty($this->provincia)) ? '' : $this->provincia.', ';
        $ret .= $this->pais;

        return $ret;
    }

    public function getOrdenTrabajo(): ?OrdenTrabajo
    {
        return $this->ordenTrabajo;
    }

    public function setOrdenTrabajo(?OrdenTrabajo $ordenTrabajo): self
    {
        $this->ordenTrabajo = $ordenTrabajo;

        // set (or unset) the owning side of the relation if necessary
        $newFormularioResultado = null === $ordenTrabajo ? null : $this;
        if ($newFormularioResultado !== $ordenTrabajo->getFormularioResultado()) {
            $ordenTrabajo->setFormularioResultado($newFormularioResultado);
        }

        return $this;
    }

    public function getMinutosTrabajado(): ?int
    {
        return $this->minutosTrabajado;
    }

    public function setMinutosTrabajado(?int $minutosTrabajado): self
    {
        $this->minutosTrabajado = $minutosTrabajado;

        return $this;
    }

    public function getMinutosReales(): ?int
    {
        return $this->minutosReales;
    }

    public function setMinutosReales(?int $minutosReales): self
    {
        $this->minutosReales = $minutosReales;

        return $this;
    }
}
