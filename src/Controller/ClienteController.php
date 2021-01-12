<?php

namespace App\Controller;

use App\Entity\Cliente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class ClienteController extends EasyAdminController
{
    /**
     * @Route("/cliente/sin/usuarios")
     */
    public function clienteSinUsuarios(Request $request)
    {
        $this->request = $request;
        $term = $this->request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $clientes = $em->getRepository(Cliente::class)->findByUserNullLike($term);

        $results = [];
        foreach ($clientes as $key => $cliente) {
            $results[] = [
                'id' => $cliente->getId(),
                'text' => $cliente->__toString(),
            ];
        }

        return new JsonResponse([
            'results' => $results,
        ]);
    }
}
