<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PropiedadModulo.
 *
 * @ORM\Table(name="propiedad_modulo")
 * @ORM\Entity(repositoryClass="App\Repository\PropiedadModuloRepository")
 */
class PropiedadModulo
{
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
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $orden;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formulario", inversedBy="propiedadModulos")
     * @ORM\JoinColumn(name="formulario_id", referencedColumnName="id")
     */
    private $formulario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modulo", inversedBy="propiedadModulos")
     * @ORM\JoinColumn(name="modulo_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $modulo;

    /**
     * @var int
     *
     * @ORM\Column(name="pagina", type="integer")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $pagina;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCollection", type="boolean", nullable=false, options={"default": 0})
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $isCollection = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $paginaNombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipo", inversedBy="propiedadModulos")
     * @Groups({"read","readFormularioResultadoExpress"})
     */
    private $equipo;


    public function __toString()
    {
        return $this->id.".";
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPagina(): ?int
    {
        return $this->pagina;
    }

    public function setPagina(int $pagina): self
    {
        $this->pagina = $pagina;

        return $this;
    }

    public function getIsCollection(): ?bool
    {
        return $this->isCollection;
    }

    public function setIsCollection(bool $isCollection): self
    {
        $this->isCollection = $isCollection;

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

    public function getModulo(): ?Modulo
    {
        return $this->modulo;
    }

    public function setModulo(?Modulo $modulo): self
    {
        $this->modulo = $modulo;

        return $this;
    }

    public function isCollection()
    {
        return ($this->isCollection) ? 'Si' : 'No';
    }

    public function getPaginaNombre(): ?string
    {
        return $this->paginaNombre;
    }

    public function setPaginaNombre(?string $paginaNombre): self
    {
        $this->paginaNombre = $paginaNombre;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }
}
