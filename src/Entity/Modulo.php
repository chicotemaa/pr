<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Modulo.
 *
 * @ORM\Table(name="modulo")
 * @ORM\Entity(repositoryClass="App\Repository\ModuloRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @UniqueEntity("nombre")
 */
class Modulo
{
    use SoftDeleteableEntity;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadItem", mappedBy="modulo",cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"orden"= "ASC", "id" = "ASC"})
     * @Assert\Valid
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $propiedadItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropiedadModulo", mappedBy="modulo")
     */
    private $propiedadModulos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $titulo;

    public function __construct()
    {
        $this->propiedadItems = new ArrayCollection();
        $this->propiedadModulos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PropiedadItem[]
     */
    public function getPropiedadItems(): Collection
    {
        return $this->propiedadItems;
    }

    public function addPropiedadItem(PropiedadItem $propiedadItem): self
    {
        if (!$this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems[] = $propiedadItem;
            $propiedadItem->setModulo($this);
        }

        return $this;
    }

    public function removePropiedadItem(PropiedadItem $propiedadItem): self
    {
        if ($this->propiedadItems->contains($propiedadItem)) {
            $this->propiedadItems->removeElement($propiedadItem);
            // set the owning side to null (unless already changed)
            if ($propiedadItem->getModulo() === $this) {
                $propiedadItem->setModulo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PropiedadModulo[]
     */
    public function getPropiedadModulos(): Collection
    {
        return $this->propiedadModulos;
    }

    public function addPropiedadModulo(PropiedadModulo $propiedadModulo): self
    {
        if (!$this->propiedadModulos->contains($propiedadModulo)) {
            $this->propiedadModulos[] = $propiedadModulo;
            $propiedadModulo->setModulo($this);
        }

        return $this;
    }

    public function removePropiedadModulo(PropiedadModulo $propiedadModulo): self
    {
        if ($this->propiedadModulos->contains($propiedadModulo)) {
            $this->propiedadModulos->removeElement($propiedadModulo);
            // set the owning side to null (unless already changed)
            if ($propiedadModulo->getModulo() === $this) {
                $propiedadModulo->setModulo(null);
            }
        }

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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }
}
