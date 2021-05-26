<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteSucursalRepository")
 */
class ClienteSucursal
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
     * @ORM\Column(type="string", length=100)
     */
    private $encargado;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="clienteSucursal")
     */
    private $solicitud;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="ClienteSucursal")
     */
    private $solicituds;

    public function __construct()
    {
        $this->solicitud = new ArrayCollection();
        $this->solicituds = new ArrayCollection();
    }

    public function __toString()
    {
        return 'Cliente: '.$this->encargado.' Direccion: '.$this->direccion;
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

    public function getEncargado(): ?string
    {
        return $this->encargado;
    }

    public function setEncargado(string $encargado): self
    {
        $this->encargado = $encargado;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|Solicitud[]
     */
    public function getSolicitud(): Collection
    {
        return $this->solicitud;
    }

    public function addSolicitud(Solicitud $solicitud): self
    {
        if (!$this->solicitud->contains($solicitud)) {
            $this->solicitud[] = $solicitud;
            $solicitud->setClienteSucursal($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): self
    {
        if ($this->solicitud->contains($solicitud)) {
            $this->solicitud->removeElement($solicitud);
            // set the owning side to null (unless already changed)
            if ($solicitud->getClienteSucursal() === $this) {
                $solicitud->setClienteSucursal(null);
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
}
