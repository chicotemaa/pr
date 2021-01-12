<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
//DASHBOARD
use App\Entity\OrdenTrabajo;
use App\Entity\Solicitud;
use App\Entity\Cliente;


class AppController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/admin/dashboard/{idCliente}/{fechaDesde}/{fechaHasta}", name="dashboard")
     */
    public function dashboard($idCliente = null , $fechaDesde = null , $fechaHasta = null)
    {   
        if ($fechaDesde && $fechaDesde != 'null') {
            $fechaDesde= \DateTime::createFromFormat('d-m-Y H:i', $fechaDesde.' 00:00' );
        }

        if ($fechaHasta && $fechaHasta != 'null') {
            $fechaHasta= \DateTime::createFromFormat('d-m-Y H:i', $fechaHasta.' 24:00' );
        }
        
        // totales del chart
        $dashboardArray = [];
        $dashboardgArray = [];
        $cantGestionPorEstadosArray = [];
        $cantGestionArray = [];
        $cantEstadosArray = [];

        $dashboard = $this->getDoctrine()
            ->getRepository(OrdenTrabajo::class)
            ->findDashboard($idCliente , $fechaDesde , $fechaHasta);

        foreach ($dashboard as $key => $value) {
            array_push($dashboardArray, [
                'estado' => $value['ot']->estadoToString(),
                'estadoTotal' => (int) $value['estadoTotal'],
                'idEstado' => $value['ot']->getEstado(),
            ]);
        }

        // para los graficos
        // cant de ot por estados de gestion por estados
        $cantGestionPorEstados = $this->getDoctrine()
        ->getRepository(OrdenTrabajo::class)
        ->findCantGestionPorEstados($idCliente, $fechaDesde , $fechaHasta);

        foreach ($cantGestionPorEstados as $key => $value) {
            array_push($cantGestionPorEstadosArray, [
                    $value['ot']->estadoGestionToString() => [
                    $value['ot']->estadoToString(),
                    (int) $value['estadoTotal'],
                ],
            ]);
        }

        // cant de ot por estados de gestion
        $cantGestion = $this->getDoctrine()
        ->getRepository(OrdenTrabajo::class)
        ->findCantEstadosGestion($idCliente, $fechaDesde , $fechaHasta);

        $labels_cg = [];
        $cantidad_cg = [];
        foreach ($cantGestion as $key => $value) {
            $labels_cg[] = $value['ot']->estadoGestionToString();
            $cantidad_cg[] = (int) $value['estadoTotal'];
        }
        $cantGestionArray[] = $labels_cg;
        $cantGestionArray[] = $cantidad_cg;

        foreach ($cantGestion as $key => $value) {
            array_push($dashboardgArray, [
                'estado' => $value['ot']->estadoGestionToString(),
                'estadoTotal' => (int) $value['estadoTotal'],
                'idEstadoGestion' => $value['ot']->getEstadoGestion(),
            ]);
        }

        // cant de ot por estados de gestion
        $cantEstados = $this->getDoctrine()
        ->getRepository(OrdenTrabajo::class)
        ->findCantEstados($idCliente, $fechaDesde , $fechaHasta);

        $labels_ce = [];
        $cantidad_ce = [];
        foreach ($cantEstados as $key => $value) {
            $labels_ce[] = $value['ot']->estadoToString();
            $cantidad_ce[] = (int) $value['estadoTotal'];
        }
        $cantEstadosArray[] = $labels_ce;
        $cantEstadosArray[] = $cantidad_ce;

        // ahora las estadisticas de solicitudes por servicio y estado

        $dashboardSolEst = [];
        $dashboardSolServ = [];
        $cantSolEstArray = [];
        $cantSolServArray = [];

        // cant de solicitudes por estado
        $cantSolEst = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->findCantSolEst($idCliente, $fechaDesde , $fechaHasta);

        $labels_cse = [];
        $cantidad_cse = [];
        foreach ($cantSolEst as $key => $value) {
            $labels_cse[] = $value['se']->estadoToString();
            $cantidad_cse[] = (int) $value['estadoTotal'];
        }
        $cantSolEstArray[] = $labels_cse;
        $cantSolEstArray[] = $cantidad_cse;

        foreach ($cantSolEst as $key => $value) {
            array_push($dashboardSolEst, [
                'estado' => $value['se']->estadoToString(),
                'estadoTotal' => (int) $value['estadoTotal'],
                'idEstado' => $value['se']->getEstado(),
            ]);
        }

        // cant de solicitudes por servicio
        $cantSolServ = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->findCantSolServ($idCliente, $fechaDesde , $fechaHasta);

        $labels_css = [];
        $cantidad_css = [];
        foreach ($cantSolServ as $key => $value) {
            if ($value['se']->getServicio()) {
                $labels_css[] = $value['se']->getServicio()->__toString();
                $cantidad_css[] = (int) $value['estadoTotal'];
            }
            
        }
        $cantSolServArray[] = $labels_css;
        $cantSolServArray[] = $cantidad_css;

        foreach ($cantSolServ as $key => $value) {
            if ($value['se']->getServicio()) {
                array_push($dashboardSolServ, [
                    'estado' => $value['se']->getServicio()->__toString(),
                    'estadoTotal' => (int) $value['estadoTotal'],
                    'idServicio' => $value['se']->getServicio()->getId(),
                ]);
            }
        }

        if ($idCliente || $fechaDesde || $fechaHasta){
            return new JsonResponse([
                'dashboardg' => $dashboardgArray,
                'dashboard' => $dashboardArray,
                'cantGestionPorEstadosArray' => $cantGestionPorEstadosArray,
                'cantGestionArray' => $cantGestionArray,
                'cantEstadosArray' => $cantEstadosArray,

                // para las solicitudes
                'dashboardSolEst' => $dashboardSolEst,
                'dashboardSolServ' => $dashboardSolServ,
                'cantSolEstArray' => $cantSolEstArray,
                'cantSolServArray' => $cantSolServArray,
            ]);
        }else{
            return $this->render('app/dashboard.html.twig', [
                // para las ordenes de trabajo
                'dashboardg' => $dashboardgArray,
                'dashboard' => $dashboardArray,
                'cantGestionPorEstadosArray' => $cantGestionPorEstadosArray,
                'cantGestionArray' => $cantGestionArray,
                'cantEstadosArray' => $cantEstadosArray,
    
                // para las solicitudes
                'dashboardSolEst' => $dashboardSolEst,
                'dashboardSolServ' => $dashboardSolServ,
                'cantSolEstArray' => $cantSolEstArray,
                'cantSolServArray' => $cantSolServArray,
            ]);
        }
        
    }


    
    /**
     * @Route("/admin/buscarClientes/{textoCliente}", name="buscarClientes")
     */
    public function buscarClientes($textoCliente)
    {   
        $clientes = $this->getDoctrine()
            ->getRepository(Cliente::class)
            ->findByText($textoCliente);
        
        $listaClientes=[];
        foreach ($clientes as $key => $cliente) {
            $listaClientes[]=[
                'idCliente'=> $cliente->getId(),
                'texto'=> $cliente->getRazonSocial().' '.$cliente->getApellido().' '.$cliente->getNombre()
            ];
        }
        
        return new JsonResponse($listaClientes);
    }
}
