<?php

namespace App\Action;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Solicitud;
use Symfony\Component\HttpFoundation\Request;

class SolicitudByUser
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
        $solicitudes = $this->em->getRepository(Solicitud::class)->findByCliente($this->security->getUser()->getCliente()->getId());

        return $solicitudes;
    }
}
