<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Resultado.
 *
 * @ORM\Table(name="resultado")
 * @ORM\Entity(repositoryClass="App\Repository\ResultadoRepository")
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"readFormularioResultado", "readFormularioResultadoExpress"}},
 *     "denormalization_context"={"groups"={"writeFormularioResultado", "writeFormularioResultadoExpress"}}
 *     },
 *  collectionOperations = {
 *     "get"={"method"="GET"}
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  }
 * )
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Resultado
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255,nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="simple_array", )
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PropiedadItem", inversedBy="resultados")
     * @ORM\JoinColumn(name="propiedad_item_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $propiedadItem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormularioResultado", inversedBy="resultados")
     * @ORM\JoinColumn(name="formulario_resultado", referencedColumnName="id", onDelete="CASCADE")
     */
    private $formularioResultado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormularioResultadoExpress", inversedBy="resultados")
     * @ORM\JoinColumn(name="formulario_resultado_express", referencedColumnName="id", onDelete="CASCADE")
     */
    private $formularioResultadoExpress;

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
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="latitud", type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $latitud;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=255, nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="zoom_map_report", type="string", length=255, nullable=true)
     */
    private $zoomMapReport;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="resultado_static_map", fileNameProperty="imageStaticMapName", size="imageStaticMapSize")
     *
     * @var File
     */
    private $imageStaticMapFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageStaticMapName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $imageStaticMapSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $statiMapUpdatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $indiceItem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $indiceModulo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $idModulo;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default": 0})
     * @Groups({"writeFormularioResultado", "writeFormularioResultadoExpress", "readFormularioResultado", "readFormularioResultadoExpress"})
     */
    private $isColeccionable = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"readFormularioResultado", "readFormularioResultadoExpress"})
     *
     * @var int
     */
    private $idPropiedadItem;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $deleted;

    /**
     * @ORM\PrePersist
     */
    public function setPrePersist()
    {
        $this->idModulo = $this->propiedadItem->getModulo()->getId();
        $this->idPropiedadItem = $this->propiedadItem->getId();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {

        if(is_null($this->getFormularioResultadoExpress())) {
            return 'NULL';
        }
        return (string) $this->getFormularioResultadoExpress();    }

    public function controlValorSegunTipo()
    {
        $ret = false;

        if ($this->getPropiedadItem()->getItem()->getTipo() == 'foto' && $this->imageName) {
            $ret = true;
        } else {
            foreach ($this->valor as $v) {
                if (!empty($v)){
                    $ret = true;
                }
            }
        }

        return $ret;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getValor(): ?array
    {
        return $this->valor;
    }

    public function setValor(array $valor): self
    {
        $this->valor = $valor;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getZoomMapReport(): ?string
    {
        return $this->zoomMapReport;
    }

    public function setZoomMapReport(?string $zoomMapReport): self
    {
        $this->zoomMapReport = $zoomMapReport;

        return $this;
    }

    public function getImageStaticMapName(): ?string
    {
        return $this->imageStaticMapName;
    }

    public function setImageStaticMapName(?string $imageStaticMapName): self
    {
        $this->imageStaticMapName = $imageStaticMapName;

        return $this;
    }

    public function getImageStaticMapSize(): ?int
    {
        return $this->imageStaticMapSize;
    }

    public function setImageStaticMapSize(?int $imageStaticMapSize): self
    {
        $this->imageStaticMapSize = $imageStaticMapSize;

        return $this;
    }

    public function getStatiMapUpdatedAt(): ?\DateTimeInterface
    {
        return $this->statiMapUpdatedAt;
    }

    public function setStatiMapUpdatedAt(?\DateTimeInterface $statiMapUpdatedAt): self
    {
        $this->statiMapUpdatedAt = $statiMapUpdatedAt;

        return $this;
    }

    public function getIndiceItem(): ?int
    {
        return $this->indiceItem;
    }

    public function setIndiceItem(?int $indiceItem): self
    {
        $this->indiceItem = $indiceItem;

        return $this;
    }

    public function getIndiceModulo(): ?int
    {
        return $this->indiceModulo;
    }

    public function setIndiceModulo(?int $indiceModulo): self
    {
        $this->indiceModulo = $indiceModulo;

        return $this;
    }

    public function getIdModulo(): ?int
    {
        return $this->idModulo;
    }

    public function setIdModulo(?int $idModulo): self
    {
        $this->idModulo = $idModulo;

        return $this;
    }

    public function getIsColeccionable(): ?bool
    {
        return $this->isColeccionable;
    }

    public function setIsColeccionable(bool $isColeccionable): self
    {
        $this->isColeccionable = $isColeccionable;

        return $this;
    }

    public function getIdPropiedadItem(): ?int
    {
        return $this->idPropiedadItem;
    }

    public function setIdPropiedadItem(?int $idPropiedadItem): self
    {
        $this->idPropiedadItem = $idPropiedadItem;

        return $this;
    }

    public function getPropiedadItem(): ?PropiedadItem
    {
        return $this->propiedadItem;
    }

    public function setPropiedadItem(?PropiedadItem $propiedadItem): self
    {
        $this->propiedadItem = $propiedadItem;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile($image = null): void
    {
        $this->imageFile = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageStaticMapFile($image = null): void
    {
        $this->imageStaticMapFile = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->statiMapUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile()//: ?File
    {
        return $this->imageFile;
    }

    public function getImageStaticMapFile()//: ?File
    {
        return $this->imageStaticMapFile;
    }

    public function obtenerValorToString()
    {
        $valores = [];

        foreach ($this->valor as $valor) {
            $valores[] = $this->propiedadItem->getItem()->obternerValorDeOpcion($valor);
        }

        return (!empty($valores)) ? implode(", ", $valores) : '';
    }

    public function getFormularioResultadoExpress(): ?FormularioResultadoExpress
    {
        return $this->formularioResultadoExpress;
    }

    public function setFormularioResultadoExpress(?FormularioResultadoExpress $formularioResultadoExpress): self
    {
        $this->formularioResultadoExpress = $formularioResultadoExpress;

        return $this;
    }

    public function getDeleted(): ?string
    {
        return $this->deleted;
    }

    public function setDeleted(?string $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
