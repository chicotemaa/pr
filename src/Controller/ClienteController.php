<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\SucursalDeCliente;
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


    /**
    * @Route("/admin/facility/by/sucursaldecliente")
    */
   public function userSinLoggedUserSucursal(Request $request)
   {
       $this->request = $request;
       $term = $this->request->query->get('query');
       $session = $this->request->getSession();
       $em = $this->getDoctrine()->getManager();

       $sucursal = $this->isGranted('ROLE_ADMIN') ? null : $session->get('user_cliente', null);

       $users = $em->getRepository(SucursalDeCliente::class)->findByFacility($term, $sucursal);
       
       $results = [];
       foreach ($users as $key => $user) {
           $results[] = [
               
               'cliente'=>$user->getId()
               
           ];
       }

       return new JsonResponse([
           'results' => $results,
       ]);
   }


}
