<?php

namespace App\Action;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\OrdenTrabajo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrdenTrabajoBySucursalDeCliente
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $id = $request->get('id');
        $ordenTrabajos = $this->em->getRepository(OrdenTrabajo::class)->findAllSucursalDeCliente($id);    

        return $ordenTrabajos;
        //return new JsonResponse($ordenTrabajos);
    }
}
