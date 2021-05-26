<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as AppAssert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\Table(name="fos_user")
 * @AppAssert\UserConstraint
 * @ApiResource(
 *    attributes={
 *     "normalization_context"={"groups"={"readRegistration"}},
 *     "denormalization_context"={"groups"={"writeRegistration"}}
 *     },
 *   collectionOperations = {
 *          "post"={
 *                  "method"="POST",
 *                  "controller" = "App\Action\UserRegistration"
 *          },
 *           "getUserInfo"={
 *              "method"="GET",
 *              "path"="/user/info",
 *              "controller" = "App\Action\UserInfo",
 *              "normalization_context"={"groups"={"userInfo"}}
 *          }
 *    },
 *    itemOperations={
 *           "get"={"method"="GET"}
 *     })
 */
class User extends BaseUser   
{
    use SoftDeleteableEntity;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrdenTrabajo", mappedBy="user", orphanRemoval=true)
     */
    private $ordenTrabajo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FormularioResultadoExpress", mappedBy="user")
     */
    private $formularioResultadoExpress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sucursal")
     * @Groups({"readRegistration", "writeRegistration", "userInfo"})
     */
    private $sucursal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="user", cascade={"persist"})
     * @Groups({"readRegistration", "writeRegistration", "userInfo"})
     */
    private $cliente;

    /**
     * @Groups({"readRegistration", "writeRegistration", "userInfo"})
     */
    protected $email;

    protected $password;

    /**
     * @Groups({"readRegistration", "writeRegistration", "userInfo"})
     */
    protected $username;

    /**
     * @Groups({"readRegistration", "writeRegistration"})
     */
    protected $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group")
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ClienteSucursal", cascade={"persist", "remove"})
     */
    private $ClienteSucursal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solicitud", mappedBy="User")
     */
    private $solicituds;

        public function __construct()
    {
        parent::__construct();
        $this->ordenTrabajo = new ArrayCollection();
        $this->formularioResultadoExpress = new ArrayCollection();
        // your own logic
        $this->roles = ['ROLE_USER'];
        $this->solicituds = new ArrayCollection();
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
     * @return Collection|OrdenTrabajo[]
     */
    public function getOrdenTrabajo(): Collection
    {
        return $this->ordenTrabajo;
    }

    public function addOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if (!$this->ordenTrabajo->contains($ordenTrabajo)) {
            $this->ordenTrabajo[] = $ordenTrabajo;
            $ordenTrabajo->setUser($this);
        }

        return $this;
    }

    public function removeOrdenTrabajo(OrdenTrabajo $ordenTrabajo): self
    {
        if ($this->ordenTrabajo->contains($ordenTrabajo)) {
            $this->ordenTrabajo->removeElement($ordenTrabajo);
            // set the owning side to null (unless already changed)
            if ($ordenTrabajo->getUser() === $this) {
                $ordenTrabajo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|formularioResultadoExpress[]
     */
    public function getFormularioResultadoExpress(): Collection
    {
        return $this->formularioResultadoExpress;
    }

    public function addFormularioResultadoExpress(FormularioResultadoExpress $formularioResultadoExpress): self
    {
        if (!$this->formularioResultadoExpress->contains($formularioResultadoExpress)) {
            $this->formularioResultadoExpress[] = $formularioResultadoExpress;
            $formularioResultadoExpress->setUser($this);
        }

        return $this;
    }

    public function removeFormularioResultadoExpress(FormularioResultadoExpress $formularioResultadoExpress): self
    {
        if ($this->formularioResultadoExpress->contains($formularioResultadoExpress)) {
            $this->formularioResultadoExpress->removeElement($formularioResultadoExpress);
            // set the owning side to null (unless already changed)
            if ($formularioResultadoExpress->getUser() === $this) {
                $formularioResultadoExpress->setUser(null);
            }
        }

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

    /**
     * @return Collection|ClienteSucursal[]
     */


    public function getClienteSucursal(): ?ClienteSucursal
    {
        return $this->ClienteSucursal;
    }

    public function setClienteSucursal(?ClienteSucursal $ClienteSucursal): self
    {
        $this->ClienteSucursal = $ClienteSucursal;

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
            $solicitud->setUser($this);
        }

        return $this;
    }

    public function removeSolicitud(Solicitud $solicitud): self
    {
        if ($this->solicituds->contains($solicitud)) {
            $this->solicituds->removeElement($solicitud);
            // set the owning side to null (unless already changed)
            if ($solicitud->getUser() === $this) {
                $solicitud->setUser(null);
            }
        }

        return $this;
    }
}
