<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\GoogleAddress;
use ApiPlatform\Core\Annotation\ApiResource;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"read", "readRegistration"}},
 *     "denormalization_context"={"groups"={"write","writeRegistration"}}
 *     },
 *  collectionOperations = {
 *     "get"={"method"="GET"}
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  })
 */
class Cliente implements iSucursalFilter
{
    use GoogleAddress;
    use SoftDeleteableEntity;

    protected $ivaConditionArray = [
        3 => 'Consumidor Final',
        5 => 'Exento',
        6 => 'Exterior',
        7 => 'IVA No Alcanzado',
        4 => 'Monotributista',
        1 => 'Responsable Inscripto',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"userInfo"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","readList"})
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","readList"})
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","readList"})
     */
    private $razonSocial;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="cliente", orphanRemoval=true)
     */
    private $ordenTrabajos;

    /**
     * @var string
     * @ORM\Column(name="street", type="string", length=100, nullable=true)
     * @Groups({"read","readList"})
     * @Assert\NotBlank()
     */
    protected $street;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readRegistration", "writeRegistration"})
     */
    private $condicionIVA;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $cuit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sucursal")
     */
    private $sucursal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="cliente", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="cliente")
     */
    private $solicituds;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Carpeta", inversedBy="clientes")
     */
    private $carpeta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Equipo", mappedBy="cliente")
     */
    private $equipos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $latitud;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $longitud;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SucursalDeCliente", mappedBy="Cliente")
     */
    private $sucursalDeClientes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facility", mappedBy="Cliente")
     */
    private $facilities;






    public function __construct()
    {
        $this->ordenTrabajos = new ArrayCollection();
        $this->solicituds = new ArrayCollection();
        $this->equipos = new ArrayCollection();
        $this->sucursalDeClientes = new ArrayCollection();
        $this->SucursalDeCliente = new ArrayCollection();
        $this->facilities = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return (string) $this->razonSocial.' '.$this->nombre.' '.$this->street; // If it is possible, return a string value from object.
         } catch (Exception $e) {
            return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
         }
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

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (empty($this->nombre) && empty($this->apellido) && empty($this->razonSocial)) {
            $context->buildViolation('Debe completar al menos 1 campo para identificar al cliente. (Nombre, apellido, o razon social)')
                ->addViolation();
        }

        if (!empty($this->cuit) && !$this->validarCuit($this->cuit)) {
            $context->buildViolation('El CUIT no es correcto')
                ->addViolation();
        }
    }

    public function getIvaConditionString(): ?string
    {
        return $this->ivaConditionArray[$this->condicionIVA];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }
    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->apellido = $street;

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

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function setRazonSocial(string $razonSocial): self
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    /**
     * @return Collection|OrdenTrabajo[]
     */
    public function getOrdenTrabajos(): Collection
    {
        return $this->ordenTrabajos;
    }

    public function addOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if (!$this->ordenTrabajos->contains($ordenTrabajo)) {
            $this->ordenTrabajos[] = $ordenTrabajo;
            $ordenTrabajo->setCliente($this);
        }

        return $this;
    }

    public function removeOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if ($this->ordenTrabajos->contains($ordenTrabajo)) {
            $this->ordenTrabajos->removeElement($ordenTrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenTrabajo->getCliente() === $this) {
                $ordenTrabajo->setCliente(null);
            }
        }

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getCondicionIVA(): ?string
    {
        return $this->condicionIVA;
    }

    public function setCondicionIVA(string $condicionIVA): self
    {
        $this->condicionIVA = $condicionIVA;

        return $this;
    }

    public function getCuit(): ?int
    {
        return $this->cuit;
    }

    public function setCuit(?int $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function validarCuit($cuit)
    {
        $cuit = preg_replace('/[^\d]/', '', (string) $cuit);
        if (11 != strlen($cuit)) {
            return false;
        }
        $acumulado = 0;
        $digitos = str_split($cuit);
        $digito = array_pop($digitos);

        for ($i = 0; $i < count($digitos); ++$i) {
            $acumulado += $digitos[9 - $i] * (2 + ($i % 6));
        }
        $verif = 11 - ($acumulado % 11);
        $verif = 11 == $verif ? 0 : $verif;

        return $digito == $verif;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newCliente = null === $user ? null : $this;
        if ($newCliente !== $user->getCliente()) {
            $user->setCliente($newCliente);
        }

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicituds(): Collection
    {
        return $this->solicituds;
    }

    public function addSolicitud(Solicitud $solicitud): self
    {
        if (!$this->solicituds->contains($solicitud)) {
            $this->solicituds[] = $solicitud;
            $solicitud->setCliente($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): self
    {
        if ($this->solicituds->contains($solicitud)) {
            $this->solicituds->removeElement($solicitud);
            // set the owning side to null (unless already changed)
            if ($solicitud->getCliente() === $this) {
                $solicitud->setCliente(null);
            }
        }

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCarpeta(): ?Carpeta
    {
        return $this->carpeta;
    }

    public function setCarpeta(?Carpeta $carpeta): self
    {
        $this->carpeta = $carpeta;

        return $this;
    }

    /**
     * @return Collection|Equipo[]
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Equipo $equipo): self
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos[] = $equipo;
            $equipo->setCliente($this);
        }

        return $this;
    }

    public function removeEquipo(Equipo $equipo): self
    {
        if ($this->equipos->contains($equipo)) {
            $this->equipos->removeElement($equipo);
            // set the owning side to null (unless already changed)
            if ($equipo->getCliente() === $this) {
                $equipo->setCliente(null);
            }
        }

        return $this;
    }

    public function getLatitud(): ?int
    {
        return $this->latitud;
    }

    public function setLatitud(?int $latitud): self
    {
        $this->latitud = $latitud;

        return $this;
    }

    public function getLongitud(): ?int
    {
        return $this->longitud;
    }

    public function setLongitud(?int $longitud): self
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * @return Collection|SucursalDeCliente[]
     */
    public function getSucursalDeClientes(): Collection
    {
        return $this->sucursalDeClientes;
    }

    public function addSucursalDeCliente(SucursalDeCliente $sucursalDeCliente): self
    {
        if (!$this->sucursalDeClientes->contains($sucursalDeCliente)) {
            $this->sucursalDeClientes[] = $sucursalDeCliente;
            $sucursalDeCliente->setCliente($this);
        }

        return $this;
    }

    public function removeSucursalDeCliente(SucursalDeCliente $sucursalDeCliente): self
    {
        if ($this->sucursalDeClientes->contains($sucursalDeCliente)) {
            $this->sucursalDeClientes->removeElement($sucursalDeCliente);
            // set the owning side to null (unless already changed)
            if ($sucursalDeCliente->getCliente() === $this) {
                $sucursalDeCliente->setCliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SucursalDeCliente[]
     */
    public function getSucursalDeCliente(): Collection
    {
        return $this->SucursalDeCliente;
    }

    /**
     * @return Collection|Facility[]
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function addFacility(Facility $facility): self
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities[] = $facility;
            $facility->setCliente($this);
        }

        return $this;
    }

    public function removeFacility(Facility $facility): self
    {
        if ($this->facilities->contains($facility)) {
            $this->facilities->removeElement($facility);
            // set the owning side to null (unless already changed)
            if ($facility->getCliente() === $this) {
                $facility->setCliente(null);
            }
        }

        return $this;
    }


}
