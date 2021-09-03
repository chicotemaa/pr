<?php

namespace App\Action;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OrdenTrabajo;
use Symfony\Component\HttpFoundation\Request;

class OrdenTrabajoList
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
        $ordenTrabajos = $this->em->getRepository(OrdenTrabajo::class)->findAll();


        return $ordenTrabajos;
    }
}
