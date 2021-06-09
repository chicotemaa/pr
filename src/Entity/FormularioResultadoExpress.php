<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Validator\Constraints as AppAssert;
use DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * FormularioResultadoExpress.
 *
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"readFormularioResultadoExpress"}},
 *     "denormalization_context"={"groups"={"writeFormularioResultadoExpress"}}
 *     },
 * collectionOperations = {
 *         "ByUser" = {
 *             "method" =  "GET",
 *             "path" = "/formularioResultadoExpress/by/user",
 *             "controller" = "App\Action\FormularioByExpress",
 *             "normalization_context"={"groups"={"readFormularioResultadoExpress"}},
 *             "denormalization_context"={"groups"={"writeFormularioResultadoExpress"}}
 *        },
 *          "ByUserWithOutForm" = {
 *             "method" =  "GET",
 *             "path" = "/formularioResultadoExpress/by/user/without-form",
 *             "controller" = "App\Action\FormularioByExpress",
 *             "normalization_context"={"groups"={"readFormularioResultadoExpress"}},
 *             "denormalization_context"={"groups"={"writeFormularioResultadoExpress"}}
 *        },
 *          "ByCM" = {
 *             "method" =  "GET",
 *             "path" = "/formularioResultadoExpress/by/user/cm",
 *             "controller" = "App\Action\FormularioByCM",
 *             "normalization_context"={"groups"={"readFormularioResultadoExpress"}},
 *             "denormalization_context"={"groups"={"writeFormularioResultadoExpress"}}
 *        },
 *       "post"
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"},
 *          "put"={"method"="PUT"},
 *  }
 * )
 * @ORM\Table(name="formulario_resultado_express")
 * @ORM\Entity(repositoryClass="App\Repository\FormularioResultadoExpressRepository")
 */
class FormularioResultadoExpress
{
    use TimestampableEntity;

    public static $estados = [
        0 => 'Pendiente',
        1 => 'Estoy en camino',
        2 => 'Me recibio',
        3 => 'No me atendio',
        4 => 'Finalizado',
        5 => 'Postergado',
        6 => 'Pendiente de revision',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     * @Groups({"readFormularioResultadoExpress"})
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resultado", mappedBy="formularioResultadoExpress",cascade={"all"}, orphanRemoval=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     * @ORM\OrderBy({"id" = "ASC", "indiceModulo" = "ASC", "indiceItem" = "ASC"})
     */
    private $resultados;

    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=255)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=255)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
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
     * @Groups({"readFormularioResultadoExpress"})
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="indetificacion", type="string", length=255, nullable=true)
     * @Groups({"readFormularioResultadoExpress"})
     */
    private $indetificacion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $minutosTrabajado;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $estado = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $horaInicio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $cliente;

    /**
     * @ORM\Column(type="date")
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $motivo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulario", inversedBy="formularioExpress")
     * @Groups({"writeFormularioResultadoExpress", "readFormularioResultadoExpress"})
     */
    private $formulario;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="resultado_imagen", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $imageSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $responsableFirma;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="formularioResultadoExpress")
     */
    private $user;

    private $resultadosOrdenados = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"writeFormularioResultadoExpress","readFormularioResultadoExpress"})
     */
    private $compraMateriales;

    public function __construct()
    {
        $this->fecha = new DateTime('now');
        $this->resultados = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Cliente: '.$this->cliente.'| Usuario: '.$this->user.'| Formulario'.$this->formulario;
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
            $resultado->setFormularioResultadoExpress($this);
        }

        return $this;
    }

    public function removeResultado(Resultado $resultado): self
    {
        if ($this->resultados->contains($resultado)) {
            $this->resultados->removeElement($resultado);
            // set the owning side to null (unless already changed)
            if ($resultado->getFormularioResultadoExpress() === $this) {
                $resultado->setFormularioResultadoExpress(null);
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
    
    public function getMinutosTrabajado(): ?int
    {
        return $this->minutosTrabajado;
    }

    public function setMinutosTrabajado(?int $minutosTrabajado): self
    {
        $this->minutosTrabajado = $minutosTrabajado;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getHoraInicio(): ?\DateTimeInterface
    {
        return $this->horaInicio;
    }

    public function setHoraInicio(?\DateTimeInterface $horaInicio): self
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    public function getCliente(): ?string
    {
        return $this->cliente;
    }

    public function setCliente(?string $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    public function setMotivo(?string $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getFormulario(): ?Formulario
    {
        return $this->formulario;
    }

    public function setFormulario(?Formulario $formulario): self
    {
        $this->formulario = $formulario;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

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

    public function getResponsableFirma(): ?string
    {
        return $this->responsableFirma;
    }

    public function setResponsableFirma(string $responsableFirma): self
    {
        $this->responsableFirma = $responsableFirma;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function mergeResultadoFormulario()
    {
        if ($this->resultadosOrdenados) {
            return $this->resultadosOrdenados;
        }

        $this->resultadosOrdenados = [];
        if ($this->resultados) {
            foreach ($this->resultados as $resultado) {
                if ($resultado->controlValorSegunTipo($resultado)) {
                    $this->resultadosOrdenados[$resultado->getPropiedadItem()->getModulo()->getId()][$resultado->getIndiceModulo()][$resultado->getPropiedadItem()->getId()][] = $resultado;
                }
            }
        }

        return $this->resultadosOrdenados;
    }

    public function arrayIndiceModulo($array, $moduloId)
    {
        if (isset($array[$moduloId])) {
            ++$array[$moduloId];
        } else {
            $array[$moduloId] = 0;
        }

        return $array;
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
}
