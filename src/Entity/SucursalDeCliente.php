<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SucursalDeClienteRepository")
  * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"read", "readRegistration"}},
 *     "denormalization_context"={"groups"={"write","writeRegistration"}}
 *     },
 *  collectionOperations = {
 *     "get"={"method"="GET"},
 *    "ByUser" = {
 *             "method" =  "GET",
 *             "path" = "/sucursalcliente/by/user",
 *             "controller" = "App\Action\SucursalDeClienteByUser",
 *             "normalization_context"={"groups"={"read"}},
 *             "denormalization_context"={"groups"={"write"}}
 *        },
 *   "List" = {
 *             "method" =  "GET",
 *             "path" = "/sucursaldecliente/",
 *             "normalization_context"={"groups"={"read", "List"}},
 *             "denormalization_context"={"groups"={"write","writeRegistration"}}
 *        }
 *  },
 *  itemOperations={
 *          "get"={"method"="GET"}
 *  })
 */
class SucursalDeCliente
implements iSucursalFilter, iClienteFilter, iFacilityFilter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")    
     * @Groups({"read","List"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * @Groups({"readRegistration", "List"})
     */
    private $codigo;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="sucursalDeClientes")
     *  @Groups({"readRegistration"})
     */
    private $Cliente;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"readRegistration", "List"})
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="SucursalDeCliente" ,cascade={"persist", "remove"})
     * 
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="SucursalDeCliente" , cascade={"persist", "remove"})
     * 
     */
    private $ordenTrabajos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="SucursalDeCliente" , cascade={"persist", "remove"})
     *  
     */
    private $solicituds;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facility", inversedBy="sucursalDeClientes")
     * @Groups({ "List"})
     */
    private $facility;

    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sucursal", inversedBy="sucursalDeClientes")
     */
    private $Sucursal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Latitud;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Longitud;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->ordenTrabajos = new ArrayCollection();
        $this->solicituds = new ArrayCollection();
    }
    public function __toString()
    {

        return $this->codigo.' || ' . $this->direccion;

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



    public function getCliente(): ?Cliente
    {
        return $this->Cliente;
    }

    public function setCliente(?Cliente $Cliente): self
    {
        $this->Cliente = $Cliente;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

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
            $user->setSucursalDeCliente($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSucursalDeCliente() === $this) {
                $user->setSucursalDeCliente(null);
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
            $ordenTrabajo->setSucursalDeCliente($this);
        }

        return $this;
    }

    public function removeOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if ($this->ordenTrabajos->contains($ordenTrabajo)) {
            $this->ordenTrabajos->removeElement($ordenTrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenTrabajo->getSucursalDeCliente() === $this) {
                $ordenTrabajo->setSucursalDeCliente(null);
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
            $solicitud->setSucursalDeCliente($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): self
    {
        if ($this->solicituds->contains($solicitud)) {
            $this->solicituds->removeElement($solicitud);
            // set the owning side to null (unless already changed)
            if ($solicitud->getSucursalDeCliente() === $this) {
                $solicitud->setSucursalDeCliente(null);
            }
        }

        return $this;
    }

    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    public function setFacility(?Facility $facility): self
    {
        $this->facility = $facility;

        return $this;
    }

    public function getSucursal(): ?Sucursal
    {
        return $this->Sucursal;
    }

    public function setSucursal(?Sucursal $Sucursal): self
    {
        $this->Sucursal = $Sucursal;

        return $this;
    }

    public function getLatitud(): ?float
    {
        return $this->Latitud;
    }

    public function setLatitud(float $Latitud): self
    {
        $this->Latitud = $Latitud;

        return $this;
    }

    public function getLongitud(): ?float
    {
        return $this->Longitud;
    }

    public function setLongitud(float $Longitud): self
    {
        $this->Longitud = $Longitud;

        return $this;
    }
}
