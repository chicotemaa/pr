<?php

namespace App\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrdenTrabajoDelete
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke($data)
    {
        $response = new JsonResponse();

            $this->em->remove($data);
            $this->em->flush();

        

        return $response;
    }
}
