<?php

namespace App\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SolicitudDelete
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke($data)
    {
        $response = new JsonResponse();

        if (!$data->getOrdenTrabajo()) {
            $this->em->remove($data);
            $this->em->flush();

            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        } else {
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $response;
    }
}
