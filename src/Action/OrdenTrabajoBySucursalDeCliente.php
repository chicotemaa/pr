<?php

namespace App\Action;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OrdenTrabajo;
use Symfony\Component\HttpFoundation\Request;

class OrdenTrabajoBySucursalDeCliente
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke(Request $request)
    {
        $SucursalDeCliente = $request->files->get('SucursalDeCliente');

        $ordenTrabajos = $this->em->getRepository(OrdenTrabajo::class)->findBySucursalDeCliente($this->security->getUser()->getSucursalDeCliente()->getId(),$SucursalDeCliente);
        

        return $ordenTrabajos;
    }
}
