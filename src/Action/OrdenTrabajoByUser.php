<?php

namespace App\Action;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OrdenTrabajo;
use Symfony\Component\HttpFoundation\Request;

class OrdenTrabajoByUser
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
        $estados = $request->query->get('estado', null);

        if ($estados) {
            $ordenTrabajos = $this->em->getRepository(OrdenTrabajo::class)->findByUserEstado($this->security->getUser()->getId(), $estados);
        } else {
            $ordenTrabajos = $this->em->getRepository(OrdenTrabajo::class)->findByUser($this->security->getUser()->getId());
        }

        return $ordenTrabajos;
    }
}
