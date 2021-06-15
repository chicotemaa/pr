<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacilityRepository")
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
class Facility
implements iClienteFilter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read"})
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $nombre;


    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $codigo;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="facilities")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $Cliente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="Facility")     
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="Facility")
     * 
     */
    private $ordenTrabajos;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="Facility")
     * 
     */
    private $solicituds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SucursalDeCliente", mappedBy="facility")
     * @Groups({"read"})
     */
    private $sucursalDeClientes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->ordenTrabajos = new ArrayCollection();
        $this->solicituds = new ArrayCollection();
        $this->sucursalDeClientes = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->Cliente . '| Nombre: ' . $this->nombre . ' ' . $this->apellido;
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }


    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }


    public function getCliente(): ?Cliente
    {
        return $this->Cliente;
    }

    public function setCliente(?Cliente $Cliente): self
    {
        $this->Cliente = $Cliente;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setFacility($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getFacility() === $this) {
                $user->setFacility(null);
            }
        }

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
            $ordenTrabajo->setFacility($this);
        }

        return $this;
    }

    public function removeOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if ($this->ordenTrabajos->contains($ordenTrabajo)) {
            $this->ordenTrabajos->removeElement($ordenTrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenTrabajo->getFacility() === $this) {
                $ordenTrabajo->setFacility(null);
            }
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
            $solicitud->setFacility($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): self
    {
        if ($this->solicituds->contains($solicitud)) {
            $this->solicituds->removeElement($solicitud);
            // set the owning side to null (unless already changed)
            if ($solicitud->getFacility() === $this) {
                $solicitud->setFacility(null);
            }
        }

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
            $sucursalDeCliente->setFacility($this);
        }

        return $this;
    }

    public function removeSucursalDeCliente(SucursalDeCliente $sucursalDeCliente): self
    {
        if ($this->sucursalDeClientes->contains($sucursalDeCliente)) {
            $this->sucursalDeClientes->removeElement($sucursalDeCliente);
            // set the owning side to null (unless already changed)
            if ($sucursalDeCliente->getFacility() === $this) {
                $sucursalDeCliente->setFacility(null);
            }
        }

        return $this;
    }
}
