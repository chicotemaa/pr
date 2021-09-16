<?php

namespace App\Action;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\SucursalDeCliente;
use Symfony\Component\HttpFoundation\Request;

class SucursalDeClienteByUser
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
        
        $SucursalDeCliente = $this->em->getRepository(SucursalDeCliente::class)->findAll();

        return $SucursalDeCliente;
    }
}
