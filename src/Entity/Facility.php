<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FacilityRepository")
 */
class Facility
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;


    /**
     * @ORM\Column(type="string", length=100)
     */
    private $correo;

    /**
     * @ORM\Column(type="integer")
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="facilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Cliente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="Facility")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="Facility")
     */
    private $ordenTrabajos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="facility")
     */
    private $no;

    public function __construct()
    {
        $this->facility = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->ordenTrabajos = new ArrayCollection();
        $this->no = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->Cliente.'| Nombre: '.$this->nombre.' '.$this->apellido;
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


    /**
     * @return Collection|Cliente[]
     */
    public function getFacility(): Collection
    {
        return $this->facility;
    }

    public function addFacility(Cliente $facility): self
    {
        if (!$this->facility->contains($facility)) {
            $this->facility[] = $facility;
            $facility->setFacility($this);
        }

        return $this;
    }

    public function removeFacility(Cliente $facility): self
    {
        if ($this->facility->contains($facility)) {
            $this->facility->removeElement($facility);
            // set the owning side to null (unless already changed)
            if ($facility->getFacility() === $this) {
                $facility->setFacility(null);
            }
        }

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
     * @return Collection|OrdenTrabajo[]
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(OrdenTrabajo $no): self
    {
        if (!$this->no->contains($no)) {
            $this->no[] = $no;
            $no->setFacility($this);
        }

        return $this;
    }

    public function removeNo(OrdenTrabajo $no): self
    {
        if ($this->no->contains($no)) {
            $this->no->removeElement($no);
            // set the owning side to null (unless already changed)
            if ($no->getFacility() === $this) {
                $no->setFacility(null);
            }
        }

        return $this;
    }
}
