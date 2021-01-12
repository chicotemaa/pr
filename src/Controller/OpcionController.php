<?php

namespace App\Controller;

use App\Entity\Opcion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class OpcionController extends EasyAdminController
{
    /**
     * @Route("/admin/opcion/by/propiedaditems")
     */
    public function opcionByPropiedadItems(Request $request)
    {
        $this->request = $request;
        $propiedadItem = $this->request->query->get('propiedadItem');
        $term = $this->request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $opciones = $em->getRepository(Opcion::class)->findByPropiedadItem($propiedadItem, $term);

        $results = [];
        foreach ($opciones as $key => $opcion) {
            $results[] = [
                'id' => $opcion->getId(),
                'text' => $opcion->__toString(),
            ];
        }

        return new JsonResponse([
            'results' => $results,
        ]);
    }
}
