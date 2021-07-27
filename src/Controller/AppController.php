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

    /**
    * @Route("/orden_trabajo/sucursaldecliente/{id}/{ultimo}")
    */
    public function OTxSucursalDeCliente($id, $ultimo)
    {
       
        $em = $this->getDoctrine()->getManager();
        
        $ordenesTrabajo = $this->getDoctrine()->getRepository(OrdenTrabajo::class)->findBySucursalDeCliente($id,$ultimo);
 
            $results=[];
            foreach ($ordenesTrabajo as $key => $ordenTrabajo) {

                $getServicio=$ordenTrabajo->getServicio();
                if ($getServicio) {
                    $servicio=['titulo'=>$getServicio->getTitulo(),
                                 'descripcion'=>$getServicio->getDescripcion()];    
                }else{
                    $servicio="null";
                }
                $getFormulario=$ordenTrabajo->getFormulario();
                $getPropiedadModulos=$getFormulario->getPropiedadModulos();
                
                
                if ($getPropiedadModulos) {
                    $PropiedadModulos=[];
                    

                    foreach ($getPropiedadModulos as $key => $getPropiedadModulos) {
                        
                        $getModulo=$getPropiedadModulos->getModulo();
                        if ($getModulo) {
                            $getPropiedadItems=$getModulo->getPropiedadItems();
                            $propiedadItems=[];
                            foreach ($getPropiedadItems as $key => $getPropiedadItems) {
                                $getItem=$getPropiedadItems->getItem();
                                $opciones=$getItem->getOpciones();
                                $opcion=[];
                                    foreach ($opciones as $key => $opciones) {
                                        $opcion[]=[
                                            'id'=>$opciones->getId(),
                                            'nombre'=>$opciones->getNombre(),
                                            'imagen'=>$opciones->getImagen(),
                                            'imagenSize'=>$opciones->getImageSize()
                                        ];
                                    }    
                                    $items=[
                                        '@id'=>'api/items/'.$getItem->getId(),
                                        '@type'=>'Item',
                                        'id'=>$getItem->getId(),
                                        'nombre'=>$getItem->getNombre(),
                                        'titulo'=>$getItem->getTitulo(),
                                        'descripcion'=>$getItem->getDescripcion(),
                                        'tipo'=>$getItem->getTipo(),
                                        'opciones'=>$opcion
                                    ];
                                $propiedadItems[]=[
                                    '@id'=>'/api/propiedad_items/'.$getPropiedadItems->getId(),
                                    '@type'=>'PropiedadItem',
                                    'id'=>$getPropiedadItems->getId(),
                                    'orden'=>$getPropiedadItems->getOrden(),
                                    'ancho'=>$getPropiedadItems->getAncho(),
                                    'requerido'=>$getPropiedadItems->getRequerido(),
                                    'opcion'=>$getPropiedadItems->getOpcion(),
                                    'items'=>$items
                                ];
                            }
                            $Modulo=['id'=>$getModulo->getId(),
                                     'propiedadItems'=> $propiedadItems,
                                     'titulo'=>$getFormulario->getTitulo()];    
                        }
                    $PropiedadModulos[]=[
                                         'id'=>$getPropiedadModulos->getId(),
                                         'orden'=>$getPropiedadModulos->getOrden(),
                                         'modulo'=>$Modulo];
                    }    
                }
                
                if (($getFormulario)) {
                    $getExpress=$getFormulario->getExpress();
                    $getCompraMateriales=$getFormulario->getCompraMateriales();
                if ($getExpress) {
                    $express=['titulo'=>$getFormulario->getExpress()];    
                }else{
                    $express="null";
                }
                if ($getCompraMateriales) {
                    $CompraMateriales=$getFormulario->getCompraMateriales();    
                }else{
                    $CompraMateriales="null";
                }

                    $Formulario=['@id'=>"/api/servicios/".$getFormulario->getId(),
                                 '@type'=>'Formulario',
                                 'id'=>$getFormulario->getId(),
                                 'titulo'=>$getFormulario->getTitulo(),
                                 'descripcion'=>$getFormulario->getDescripcion(),
                                 'propiedadModulos'=>$PropiedadModulos,
                                 'updatedAt'=>$getFormulario->getUpdatedAt()->format('c'),
                                 'version'=>$getFormulario->getVersion(),
                                 'express'=>$express,
                                 'compramateriales'=>$CompraMateriales
                                ];
                    
                }else {
                    $Formulario="null";
                }

                $results[]=[
                    'context'=>'/api/context/OrdenTrabajo',
                    '@id'=>'/api/orden_trabajo/'.$ordenTrabajo->getId(),
                    'type'=>'OrdenTrabajo',
                    'id'=> $ordenTrabajo->getId(),
                    'servicio'=>$servicio,
                    'formulario'=>$Formulario,
                    'cliente'=> $ordenTrabajo->getCliente()->getRazonSocial(),
                    'sucursalcliente'=> $ordenTrabajo->getSucursalDeCliente()->getDireccion(),

                   ];
            }
 
        return new JsonResponse([
            'OrdenTrabajo' => $results,
        ]);
    }
    /**
    * @Route("/orden_trabajo/facility/{id}/{ultimo}")
    */
    public function OTxFacility($id, $ultimo)
    {
       
        $em = $this->getDoctrine()->getManager();
        
        $ordenesTrabajo = $this->getDoctrine()->getRepository(OrdenTrabajo::class)->findByFacility($id,$ultimo);
 
            $results=[];
            foreach ($ordenesTrabajo as $key => $ordenTrabajo) {

                $getServicio=$ordenTrabajo->getServicio();
                if ($getServicio) {
                    $servicio=['titulo'=>$getServicio->getTitulo(),
                                 'descripcion'=>$getServicio->getDescripcion()];    
                }else{
                    $servicio="null";
                }
                $getFormulario=$ordenTrabajo->getFormulario();
                $getPropiedadModulos=$getFormulario->getPropiedadModulos();
                
                
                if ($getPropiedadModulos) {
                    $PropiedadModulos=[];
                    

                    foreach ($getPropiedadModulos as $key => $getPropiedadModulos) {
                        
                        $getModulo=$getPropiedadModulos->getModulo();
                        if ($getModulo) {
                            $getPropiedadItems=$getModulo->getPropiedadItems();
                            $propiedadItems=[];
                            foreach ($getPropiedadItems as $key => $getPropiedadItems) {
                                $getItem=$getPropiedadItems->getItem();
                                $opciones=$getItem->getOpciones();
                                $opcion=[];
                                    foreach ($opciones as $key => $opciones) {
                                        $opcion[]=[
                                            'id'=>$opciones->getId(),
                                            'nombre'=>$opciones->getNombre(),
                                            'imagen'=>$opciones->getImagen(),
                                            'imagenSize'=>$opciones->getImageSize()
                                        ];
                                    }    
                                    $items=[
                                        '@id'=>'api/items/'.$getItem->getId(),
                                        '@type'=>'Item',
                                        'id'=>$getItem->getId(),
                                        'nombre'=>$getItem->getNombre(),
                                        'titulo'=>$getItem->getTitulo(),
                                        'descripcion'=>$getItem->getDescripcion(),
                                        'tipo'=>$getItem->getTipo(),
                                        'opciones'=>$opcion
                                    ];
                                $propiedadItems[]=[
                                    '@id'=>'/api/propiedad_items/'.$getPropiedadItems->getId(),
                                    '@type'=>'PropiedadItem',
                                    'id'=>$getPropiedadItems->getId(),
                                    'orden'=>$getPropiedadItems->getOrden(),
                                    'ancho'=>$getPropiedadItems->getAncho(),
                                    'requerido'=>$getPropiedadItems->getRequerido(),
                                    'opcion'=>$getPropiedadItems->getOpcion(),
                                    'items'=>$items
                                ];
                            }
                            $Modulo=['id'=>$getModulo->getId(),
                                     'propiedadItems'=> $propiedadItems,
                                     'titulo'=>$getFormulario->getTitulo()];    
                        }
                    $PropiedadModulos[]=[
                                         'id'=>$getPropiedadModulos->getId(),
                                         'orden'=>$getPropiedadModulos->getOrden(),
                                         'modulo'=>$Modulo];
                    }    
                }
                
                if (($getFormulario)) {
                    $getExpress=$getFormulario->getExpress();
                    $getCompraMateriales=$getFormulario->getCompraMateriales();
                if ($getExpress) {
                    $express=['titulo'=>$getFormulario->getExpress()];    
                }else{
                    $express="null";
                }
                if ($getCompraMateriales) {
                    $CompraMateriales=$getFormulario->getCompraMateriales();    
                }else{
                    $CompraMateriales="null";
                }

                    $Formulario=['@id'=>"/api/servicios/".$getFormulario->getId(),
                                 '@type'=>'Formulario',
                                 'id'=>$getFormulario->getId(),
                                 'titulo'=>$getFormulario->getTitulo(),
                                 'descripcion'=>$getFormulario->getDescripcion(),
                                 'propiedadModulos'=>$PropiedadModulos,
                                 'updatedAt'=>$getFormulario->getUpdatedAt()->format('c'),
                                 'version'=>$getFormulario->getVersion(),
                                 'express'=>$express,
                                 'compramateriales'=>$CompraMateriales
                                ];
                    
                }else {
                    $Formulario="null";
                }

                $results[]=[
                    'context'=>'/api/context/OrdenTrabajo',
                    '@id'=>'/api/orden_trabajo/'.$ordenTrabajo->getId(),
                    'type'=>'OrdenTrabajo',
                    'id'=> $ordenTrabajo->getId(),
                    'servicio'=>$servicio,
                    'formulario'=>$Formulario,
                    'cliente'=> $ordenTrabajo->getCliente()->getRazonSocial(),
                    'sucursalcliente'=> $ordenTrabajo->getSucursalDeCliente()->getDireccion(),

                   ];
            }
 
        return new JsonResponse([
            'OrdenTrabajo' => $results,
        ]);
    }
}
