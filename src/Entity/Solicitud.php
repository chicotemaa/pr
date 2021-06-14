<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SolicitudRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ApiResource(
 * collectionOperations = {
 *         "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"read"}},
 *              "denormalization_context"={"groups"={"write"}}
 *          },
 *         "ByUser" = {
 *             "method" =  "GET",
 *             "path" = "/solicitud/by/user",
 *             "controller" = "App\Action\SolicitudByUser",
 *             "normalization_context"={"groups"={"read"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        }
 *  },
 * itemOperations={
 *          "get",
 *          "delete"={
 *              "method"="DELETE",
 *              "controller" = "App\Action\SolicitudDelete"
 *          }
 *  }
 * )
 * @Vich\Uploadable
 * @AppAssert\SolicitudConstraint
 */
class Solicitud implements iClienteFilter, iFacilityFilter, iSucursalClienteFilter
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public static $estados = [
        0 => 'Pendiente',
        1 => 'Generada OT',
        2 => 'Derivada',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="solicituds")
     * @Groups({"write", "read"})
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Servicio")
     * @Groups({"read", "write"})
     */
    private $servicio;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default": 0}))
     * @Groups({"read"})
     */
    private $estado = 0;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read", "write"})
     */
    private $consulta;

    /**
     * @Vich\UploadableField(mapping="solicitud_imagen", fileNameProperty="imagen", size="imageSize")
     * @Groups({"read", "write"})
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"read", "write"})
     *
     * @var int
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Groups({"read", "write"})
     *
     * @var \DateTime
     */
    private $updatedImageAt;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $imagen;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    protected $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrdenTrabajo", inversedBy="solicitud", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="orden_trabajo_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $ordenTrabajo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $numeroSucursal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccionSucursal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write", "read"})
     */
    private $pisoSector;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCompromiso;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroIncidencia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sucursal")
     * @Groups({"write", "read"})
     */
    private $sucursal;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read"})
     */
    private $necesitasAyuda;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $leido;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facility", inversedBy="solicituds")
     */
    private $Facility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SucursalDeCliente", inversedBy="solicituds")
     * @Groups({"write", "read"})
     */
    private $SucursalDeCliente;

    public function __toString()
    {
        return $this->nroIncidencia;
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $this->updatedImageAt = new \DateTimeImmutable();
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

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): self
    {
        $this->servicio = $servicio;

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

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getUpdatedImageAt(): ?\DateTimeInterface
    {
        return $this->updatedImageAt;
    }

    public function setUpdatedImageAt(?\DateTimeInterface $updatedImageAt): self
    {
        $this->updatedImageAt = $updatedImageAt;

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

    public function estadoToString()
    {
        return self::$estados[$this->estado];
    }

    public function getOrdenTrabajo(): ?OrdenTrabajo
    {
        return $this->ordenTrabajo;
    }

    public function setOrdenTrabajo(?OrdenTrabajo $ordenTrabajo): self
    {
        $this->ordenTrabajo = $ordenTrabajo;

        return $this;
    }

    public function getNumeroSucursal(): ?string
    {
        return $this->numeroSucursal;
    }

    public function setNumeroSucursal(string $numeroSucursal): self
    {
        $this->numeroSucursal = $numeroSucursal;

        return $this;
    }

    public function getDireccionSucursal(): ?string
    {
        return $this->direccionSucursal;
    }

    public function setDireccionSucursal(string $direccionSucursal): self
    {
        $this->direccionSucursal = $direccionSucursal;

        return $this;
    }

    public function getPisoSector(): ?string
    {
        return $this->pisoSector;
    }

    public function setPisoSector(string $pisoSector): self
    {
        $this->pisoSector = $pisoSector;

        return $this;
    }

    public function getFechaCompromiso(): ?\DateTimeInterface
    {
        return $this->fechaCompromiso;
    }

    public function setFechaCompromiso(?\DateTimeInterface $fechaCompromiso): self
    {
        $this->fechaCompromiso = $fechaCompromiso;

        return $this;
    }

    public function getNroIncidencia(): ?string
    {
        return $this->nroIncidencia;
    }

    public function setNroIncidencia(string $nroIncidencia): self
    {
        $this->nroIncidencia = $nroIncidencia;

        return $this;
    }


    public function getConsulta(): ?string
    {
        return $this->consulta;
    }

    public function setConsulta(string $consulta): self
    {
        $this->consulta = $consulta;

        return $this;
    }

    public function getSucursal(): ?Sucursal
    {
        return $this->sucursal;
    }

    public function setSucursal(?Sucursal $sucursal): self
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    public function getNecesitasAyuda(): ?string
    {
        return $this->necesitasAyuda;
    }

    public function setNecesitasAyuda(?string $necesitasAyuda): self
    {
        $this->necesitasAyuda = $necesitasAyuda;

        return $this;
    }

    public function getLeido(): ?bool
    {
        return $this->leido;
    }

    public function setLeido(?bool $leido): self
    {
        $this->leido = $leido;

        return $this;
    }

    public function getFacility(): ?Facility
    {
        return $this->Facility;
    }

    public function setFacility(?Facility $Facility): self
    {
        $this->Facility = $Facility;

        return $this;
    }

    public function getSucursalDeCliente(): ?SucursalDeCliente
    {
        return $this->SucursalDeCliente;
    }

    public function setSucursalDeCliente(?SucursalDeCliente $SucursalDeCliente): self
    {
        $this->SucursalDeCliente = $SucursalDeCliente;

        return $this;
    }
}
