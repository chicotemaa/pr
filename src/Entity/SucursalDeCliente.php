<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SucursalDeClienteRepository")
 */
class SucursalDeCliente

{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="sucursalDeClientes")
     */
    private $Cliente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="SucursalDeCliente")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="SucursalDeCliente")
     */
    private $ordenTrabajos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="SucursalDeCliente")
     */
    private $solicituds;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->ordenTrabajos = new ArrayCollection();
        $this->solicituds = new ArrayCollection();
    }
    public function __toString()
    {
        return 'Cliente: '.$this->Cliente.'| Direccion: '.$this->direccion;
          
    }
    public function getId(): ?int
    {
        return $this->id;
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

}
