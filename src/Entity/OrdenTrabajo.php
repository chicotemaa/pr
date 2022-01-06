<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdenTrabajoRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="uf_idx", columns={"user_id", "fecha"}),
 *     @ORM\Index(name="ufe_idx", columns={"user_id", "fecha", "estado"}),
 * })
 * @Vich\Uploadable
 * @ApiResource(
 * mercure=false,
 * collectionOperations = {
 *        "post"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"readPost"}},
 *              "denormalization_context"={"groups"={"writePost"}}
 *          },
 *         "ByUser" = {
 *             "method" =  "GET",
 *             "path" = "/ordentrabajo/by/user",
 *             "controller" = "App\Action\OrdenTrabajoByUser",
 *             "normalization_context"={"groups"={"read"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        },
 *          "ByUserWithOutForm" = {
 *             "method" =  "GET",
 *             "path" = "/ordentrabajo/by/user/without-form",
 *             "controller" = "App\Action\OrdenTrabajoByUser",
 *             "normalization_context"={"groups"={"readList"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        },
 *          "BySucursalDeCliente" = {
 *             "method" =  "GET",
 *             "path" = "/orden_trabajo/{id}",
 *             "controller" = "App\Action\OrdenTrabajoBySucursalDeCliente",
 *             "normalization_context"={"groups"={"read"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        }
 * ,
 *          "OTListWithOutForm" = {
 *             "method" =  "GET",
 *             "path" = "/ordentrabajo/by/list/without-form",
 *             "controller" = "App\Action\OrdenTrabajoList",
 *             "normalization_context"={"groups"={"readList","List"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        }
 *  },
 * itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"read"}},
 *              "denormalization_context"={"groups"={"write"}}
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "normalization_context"={"groups"={"read"}},
 *              "denormalization_context"={"groups"={"write"}}
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "controller" = "App\Action\OrdenTrabajoDelete"
 *          }
 *  }
 * )
 */
class OrdenTrabajo implements iSucursalFilter, iClienteFilter, iUserFilter , iFacilityFilter, iSucursalClienteFilter
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public $estados = [
        0 => 'Pendiente',
        1 => 'Estoy en camino',
        2 => 'Me recibio',
        3 => 'No me atendio',
        4 => 'Finalizado',
        5 => 'Postergado'
    ];

    public $estadosGestion = [
        0 => 'Abierta',
        1 => 'Pendiente',
        2 => 'Cerrada',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read", "readList"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Servicio")
     * @Groups({"read", "write","writePost"})
     */
    private $servicio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulario", inversedBy="ordenTrabajo")
     * @Groups({"read","write","readList", "writePost"})
     */
    private $formulario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ordenTrabajo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     * @Groups({"readList","write","writePost"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FormularioResultado", inversedBy="ordenTrabajo", cascade={"persist", "remove"})
     * @Groups({"read","readList"})
     */
    private $formularioResultado;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read","write","readList","writePost"})
     */
    private $estado = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read","write","readList"})
     */
    private $horaInicio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read"})
     */
    private $orden;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $horaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=255, nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=255, nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $longitud;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="ordenTrabajos")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id", onDelete="SET NULL")
     * @Groups({"read", "readList", "writePost"})
     */
    private $cliente;

    /**
     * @ORM\Column(type="time")
     * @Groups({"read","write","readList","writePost"})
     */
    private $horaDesde;

    /**
     * @ORM\Column(type="time")
     * @Groups({"read","write","readList", "writePost"})
     */
    private $horaHasta;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read","write","readList", "writePost"})
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write", "writePost"})
     */
    private $motivo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sucursal")
     * @Groups({"writePost","read","write","readList"})
     */
    private $sucursal;

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
     * @Groups({"read","write","readList","writeList"})
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"write","read"})
     *
     * @var int
     */
    private $imageSize;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Solicitud", mappedBy="ordenTrabajo", cascade={"persist"})
     */
    private $solicitud;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $responsableFirma;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read","write","readList","writeList","writePost"})
     */
    private $comentario;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read","write","readList","writePost"})
     */
    private $estadoGestion = 0;

    private $resultadosOrdenados = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $latitudCierre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write","readList","writeList"})
     */
    private $longitudCierre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facility", inversedBy="ordenTrabajos")
     * @Groups({"read","write", "writePost"})
     */
    private $Facility;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SucursalDeCliente", inversedBy="ordenTrabajos")
     * @Groups({"read","write","readList","writeList", "writePost"})
     */
    private $SucursalDeCliente;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firma;

    public function __toString()
    {
        return 'Cliente: '.$this->cliente.'| Usuario: '.$this->user.'| Formulario'.$this->formulario;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getServicio(): ?Servicio
    {
        return $this->servicio;
    }

    public function setServicio(?Servicio $servicio): self
    {
        $this->servicio = $servicio;

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

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    public function setHoraFin(?\DateTimeInterface $horaFin): self
    {
        $this->horaFin = $horaFin;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFormularioResultado(): ?FormularioResultado
    {
        return $this->formularioResultado;
    }

    public function setFormularioResultado(?FormularioResultado $formularioResultado): self
    {
        $this->formularioResultado = $formularioResultado;

        return $this;
    }

    public function estadoToString()
    {
        return $this->estados[$this->estado];
    }

    public function estadoGestionToString()
    {
        return $this->estadosGestion[$this->estadoGestion];
    }

    public function getLatitud(): ?string
    {
        return $this->latitud;
    }

    public function setLatitud(?string $latitud): self
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?string
    {
        return $this->longitud;
    }

    public function setLongitud(?string $longitud): self
    {
        $this->longitud = $longitud;

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

    public function getHoraDesde(): ?\DateTimeInterface
    {
        return $this->horaDesde;
    }

    public function setHoraDesde(\DateTimeInterface $horaDesde): self
    {
        $this->horaDesde = $horaDesde;

        return $this;
    }

    public function getHoraHasta(): ?\DateTimeInterface
    {
        return $this->horaHasta;
    }

    public function setHoraHasta(\DateTimeInterface $horaHasta): self
    {
        $this->horaHasta = $horaHasta;

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

    public function fechaCompleta()
    {
        return $this->fecha->format('d/m/Y').' '.$this->horaDesde->format('H:i').' - '.$this->horaHasta->format('H:i');
    }

    public function obtenerIncidencias()
    {
        $incidencias = [];
        $modulosRepetidos = [];
        $cantidadIncidencias = 0;
        if (isset($this->formulario)){
        foreach ($this->formulario->getPropiedadModulos() as $propiedadModulo) {
            $modulosRepetidos = $this->arrayIndiceModulo($modulosRepetidos, $propiedadModulo->getModulo()->getId());

            foreach ($propiedadModulo->getModulo()->getPropiedadItems() as $propiedadItem) {
                $analisisIncidencia = $this->buscarIncidenciaItem($propiedadItem, $modulosRepetidos[$propiedadModulo->getModulo()->getId()]);

                if ($analisisIncidencia['incidencia']) {
                    $incidencias[] = $analisisIncidencia['incidencia'];
                }

                if ($analisisIncidencia['tieneIncidencia']) {
                    $cantidadIncidencias++;
                }
            }
        }
        }
        return [
            'incidenciasEncontradas' => $incidencias,
            'incidenciasTotal' => $cantidadIncidencias
        ];
    }

    private function buscarIncidenciaItem($propiedadItem, $indiceModulo)
    {
        $incidencia = null;
        $tieneIncidencia = false;

        if(!$propiedadItem->getItem()->getOpciones()->isEmpty()) {
            // Obtener los resultados del item
            $resultados = $this->buscarResultadoDelItem($propiedadItem->getModulo()->getId(), $indiceModulo, $propiedadItem->getId());
            
           // Me fijo si el item tiene algun resultado
            if (!empty($resultados)) {
                $opcionesIncidencia = [];
                foreach ($propiedadItem->getItem()->getOpciones() as $opcion) {
                    if ($opcion->getIncidencia()) {
                        $tieneIncidencia = true;
                    
                        if ($this->buscarResultadoEnOpcionItem($opcion->getId(), $resultados)) {
                            $opcionesIncidencia[] = $opcion;
                        }
                    }
                }
                if (!empty($opcionesIncidencia)) {
                    $incidencia = [
                        'item' => sprintf("%s: %s", $propiedadItem->getModulo()->getTitulo(), $propiedadItem->getItem()->getTitulo()),
                        'opciones' => implode(", ", $opcionesIncidencia)
                    ];
                }
            }
        }


        return [
            'incidencia' => $incidencia,
            'tieneIncidencia' => $tieneIncidencia
        ];
    }

    private function buscarResultadoDelItem($propiedadModuloId, $moduloIndice, $propiedadItemId) 
    {
        $resultados = $this->mergeResultadoFormulario();

        if (
            isset($resultados[$propiedadModuloId]) && 
            isset($resultados[$propiedadModuloId][$moduloIndice]) &&
            isset($resultados[$propiedadModuloId][$moduloIndice][$propiedadItemId])
            ) {
                return $resultados[$propiedadModuloId][$moduloIndice][$propiedadItemId];
        }

        return null;
    }

    private function buscarResultadoEnOpcionItem($opcionNombre, $resultados) 
    {
        foreach ($resultados as $resultado) {

            if (array_search($opcionNombre, $resultado->getValor()) !== false) {
                return true;
            }
        }

        return false;
    }

    public function mergeResultadoFormulario()
    {
        if ($this->resultadosOrdenados) {
            return $this->resultadosOrdenados;
        }

        $this->resultadosOrdenados = [];
        if ($this->formularioResultado) {
            foreach ($this->formularioResultado->getResultados() as $resultado) {
                if ($resultado->controlValorSegunTipo($resultado)) {
                    $this->resultadosOrdenados[$resultado->getPropiedadItem()->getModulo()->getId()][$resultado->getIndiceModulo()][$resultado->getPropiedadItem()->getId()][] = $resultado;
                }
            }
        }

        return $this->resultadosOrdenados;
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

    public function arrayIndiceModulo($array, $moduloId)
    {
        if (isset($array[$moduloId])) {
            ++$array[$moduloId];
        } else {
            $array[$moduloId] = 0;
        }

        return $array;
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

    public function getSolicitud(): ?Solicitud
    {
        return $this->solicitud;
    }

    public function setSolicitud(?Solicitud $solicitud): self
    {
        $this->solicitud = $solicitud;

        // set (or unset) the owning side of the relation if necessary
        $newOrdenTrabajo = null === $solicitud ? null : $this;
        if ($newOrdenTrabajo !== $solicitud->getOrdenTrabajo()) {
            $solicitud->setOrdenTrabajo($newOrdenTrabajo);
        }

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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getEstadoGestion(): ?int
    {
        return $this->estadoGestion;
    }

    public function setEstadoGestion(int $estadoGestion): self
    {
        $this->estadoGestion = $estadoGestion;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getLatitudCierre(): ?string
    {
        return $this->latitudCierre;
    }

    public function setLatitudCierre(?string $latitudCierre): self
    {
        $this->latitudCierre = $latitudCierre;

        return $this;
    }

    public function getLongitudCierre(): ?string
    {
        return $this->longitudCierre;
    }

    public function setLongitudCierre(?string $longitudCierre): self
    {
        $this->longitudCierre = $longitudCierre;

        return $this;
    }

    public function getFacility(): ?Facility
    {
        return $this->Facility;
    }

    public function setFacility(?Facility $facility): self
    {
        $this->Facility = $facility;

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

    public function getFirma(): ?string
    {
        return $this->firma;
    }

    public function setFirma(?string $firma): self
    {
        $this->firma = $firma;

        return $this;
    }

}
