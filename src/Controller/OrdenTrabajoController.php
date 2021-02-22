<?php

namespace App\Controller;

use App\Entity\Solicitud;
use App\Entity\OrdenTrabajo;
use App\Entity\Sucursal;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Contracts\Translation\TranslatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\Common\Drawing;


class OrdenTrabajoController extends EasyAdminController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'vich_uploader.templating.helper.uploader_helper' => UploaderHelper::class,
            TranslatorInterface::class
        ]);
    }

    private $formularioResultado;
    private $section;
    private $table_style;
    private $table_style_titulo;
    private $table_style_footer;
    private $table_style_image;
    private $table_style_item;
    private $formato = null;
    private $resultados = null;
    private $modulosRepetido;
    private $ordenExcel ;

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        // para los filtros si hace click en uno de los item del dashboard
        $estado = $this->request->query->get('estado');
        $estadoGestion = $this->request->query->get('estadoGestion');

        if ($estado) {
            $queryBuilder
                ->where('entity.estado = :estado')
                ->setParameter('estado', $estado);
        } elseif (is_numeric($estado)) {
            $queryBuilder
                ->where('entity.estado = :estado')
                ->setParameter('estado', $estado);
        }

        if ($estadoGestion) {
            $queryBuilder
                ->where('entity.estadoGestion = :estadoGestion')
                ->setParameter('estadoGestion', $estadoGestion);
        } elseif (is_numeric($estadoGestion)) {
            $queryBuilder
                ->where('entity.estadoGestion = :estadoGestion')
                ->setParameter('estadoGestion', $estadoGestion);
        }


        $idCliente = $this->request->query->get('idCliente');

        if ($idCliente!='null' && !empty($idCliente)) {
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->andWhere('c.id = :idCliente')
            ->setParameter('idCliente', $idCliente);
        }

        $fechaDesde = $this->request->query->get('fechaDesde');
        if ($fechaDesde!='null' && !empty($fechaDesde)) {
            
            $fechaDesde= \DateTime::createFromFormat('d-m-Y H:i', $fechaDesde.' 00:00' );
            $queryBuilder
                ->andWhere('entity.fecha >= :fechaDesde')
                ->setParameter('fechaDesde', $fechaDesde);
        }

        

        $fechaHasta = $this->request->query->get('fechaHasta');
        if ($fechaHasta!='null' && !empty($fechaHasta)) {
            $fechaHasta= \DateTime::createFromFormat('d-m-Y H:i', $fechaHasta.' 24:00' );
            $queryBuilder
                ->andWhere('entity.fecha <= :fechaHasta')
                ->setParameter('fechaHasta', $fechaHasta);
        }

        return $queryBuilder;
    }

    private function setTableStyle()
    {
        $this->table_style_item = [
            'borderTopSize' => 1,
            'borderTopColor' => 'CCCCCC',
            'borderBottomSize' => 1,
            'borderBottomColor' => 'CCCCCC',
        ];

        $this->table_style_titulo = [
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 80,
        ];

        $this->table_style_footer = [
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
            'cellMargin' => 120,
        ];

        $this->table_style = [
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
            'borderColor' => '999999',
            'cellMargin' => 60,
        ];

        $this->table_style_image = [
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 10,
        ];
    }

    public function exportarAction()
    {

        $this->setTableStyle();

        //recupero el formato a exportar
        $this->formato = $this->request->get('formato');





        //obtengo arrays ids ordenes
        $ordenesTrabajo = $this->request->get('ordenes_trabajo');
        $array = explode(",", $ordenesTrabajo);

        //uso para procesar el excel
        $this->ordenExcel = $array;





            //pregunto tipo de formato
        if('PDF' == $this->formato or 'WORD' == $this->formato){

            //armo cabecera
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $this->section = $phpWord->addSection([
                'marginLeft' => 600,
                'marginRight' => 600,
                'marginTop' => 600,
                'marginBottom' => 600,
            ]);

            $phpWord->setDefaultParagraphStyle(
                array(
                    'spaceLine' => \PhpOffice\PhpWord\Shared\Converter::INCH_TO_POINT,
                    'spacing' => 38,
                )
            );

            $tablaTitulo = $this->section->addTable($this->table_style_titulo);
            $tablaTitulo->addRow();

            $tablaTitulo->addCell(1000)->addImage(
                $this->getParameter('kernel.root_dir').'/../public/images/hogar.png',
                ['width' => 220, 'align' => 'left']
            );

            //obtengo la sucursal del primer registro finalizado
            $sucursal =  $this->em->getRepository(OrdenTrabajo::class)->find($array[0])->getSucursal();

            if($sucursal){
                $textoCabecera = '';
                if(!empty($sucursal->getTextoCabecera())){
                    $textoCabecera = $sucursal->getTextoCabecera();
                    if ('PDF' == $this->formato) {
                        $textoCabecera = str_replace('<br />', ' &#10;', $textoCabecera);
                    }
                }

                \PhpOffice\PhpWord\Shared\Html::addHtml($tablaTitulo->addCell(500), $textoCabecera);

                if(!empty($sucursal->getImageCabecera())){
                    $tablaTitulo->addCell(500)->addImage(
                        $this->getParameter('kernel.root_dir').'/../public'.$this->get('vich_uploader.templating.helper.uploader_helper')->asset($sucursal, 'imageCabeceraFile'),
                        ['wrappingStyle' => 'behind', 'width' => 150, 'height' => 100, 'align' => 'rigth']
                    );
                }
            }

            //arma el pie de pagina
            $footer = $this->section->createFooter();
            $footer->addPreserveText(
                $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.piepagina'),
                ['color' => '000000', 'size' => '8'],
                ['align' => 'center']
            );
            $footer->addTextBreak(1);


            //recorro las ordenes de trabajo
            foreach ($array as $valor){

                $ordenTrabajo = $this->em->getRepository(OrdenTrabajo::class)->find($valor);
                $this->formularioResultado = $ordenTrabajo->getFormularioResultado();


                //verifica si algun form orden esta vacio
                if (!$this->formularioResultado) {
                    $this->addFlash('warning', 'El formulario no ha sido completado');

                    if ('show' == $this->request->request->get('actionReturn')) {
                        return $this->redirectToRoute('easyadmin', [
                            'action' => 'show',
                            'id' => $this->request->get('orden_trabajo'),
                            'entity' => $this->request->query->get('entity'),
                        ]);
                    } else {
                        return $this->redirectToRoute('easyadmin', [
                            'action' => 'list',
                            'entity' => $this->request->query->get('entity'),
                        ]);
                    }


                }



                $textrun = $this->section->addTextRun();

                $textrun->addText(
                    htmlspecialchars(
                        $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.titulo')
                    ),
                    ['bold' => true, 'size' => '12']
                );

                $textrun = $this->section->addTextRun();

                $textrun->addText(
                    htmlspecialchars(
                        $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.subtitulo')
                    )
                );

                $this->section->addText('', [], ['borderBottomSize' => 6]);
                $textrun = $this->section->addTextRun();

                $textrun->addText(
                    htmlspecialchars(
                        $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.cliente')
                    )
                );

                //arma tabla cliente
                $tablaCliente = $this->section->addTable($this->table_style_titulo);
                $tablaCliente->addRow();

                $textrun = $tablaCliente->addCell(1000)->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        $ordenTrabajo->getCliente()->__toString()
                    )
                );

                $textrun = $tablaCliente->addCell(1000)->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        sprintf("%s:", $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.direccion'))
                    ),
                    ['underline' => 'single']
                );

                $textrun->addText(
                    htmlspecialchars(
                        ' '.$ordenTrabajo->getCliente()->getStreet().' \n '
                    )
                );
                $tablaCliente->addRow();

                $textrun = $tablaCliente->addCell(1000)->addTextRun();

                $textrun = $tablaCliente->addCell(1000)->addTextRun();

                $textrun->addText(
                    htmlspecialchars(
                        sprintf("%s:", $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.correo'))
                    ),
                    ['underline' => 'single']
                );

                $textrun->addText(
                    htmlspecialchars(
                        $ordenTrabajo->getCliente()->getCorreo()
                    )
                );

                $textrun = $this->section->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        'Fecha de envío del formulario:'
                    ),
                    ['underline' => 'single']
                );
                $textrun->addText(
                    htmlspecialchars(
                        ' '.$this->formularioResultado->getCreatedAt()->format('d/m/Y H:i')
                    )
                );

                $textrun = $this->section->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        'Estado:'
                    ),
                    ['underline' => 'single']
                );
                $textrun->addText(
                    htmlspecialchars(
                        ' '.$this->formularioResultado->getOrdenTrabajo()->estadoToString()
                    )
                );


                if (5 == $this->formularioResultado->getOrdenTrabajo()->getEstado()) {
                    $textrun = $this->section->addTextRun();
                    $textrun->addText(
                        htmlspecialchars(
                            'Motivo:'
                        ),
                        ['underline' => 'single']
                    );
                    $textrun->addText(
                        htmlspecialchars(
                            ' '.$this->formularioResultado->getOrdenTrabajo()->getMotivo()
                        )
                    );
                }
                $textrun = $this->section->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        'Hora Inicio - Fin:'
                    ),
                    ['underline' => 'single']
                );

                $horaInicio = ($this->formularioResultado->getOrdenTrabajo()->getHoraInicio())
                    ? $this->formularioResultado->getOrdenTrabajo()->getHoraInicio()->format('H:i') : '';

                $horaFin = ($this->formularioResultado->getOrdenTrabajo()->getHoraFin())
                    ? $this->formularioResultado->getOrdenTrabajo()->getHoraFin()->format('H:i') : '';
                $textrun->addText(
                    htmlspecialchars(
                        ' '.$horaInicio.' - '.$horaFin
                    )
                );
                $textrun = $this->section->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        'Minutos Trabajados:'
                    ),
                    ['underline' => 'single']
                );
                $textrun->addText(
                    htmlspecialchars(
                        ' '.$this->formularioResultado->getMinutosTrabajado()
                    )
                );
                $textrun = $this->section->addTextRun();
                $textrun->addText(
                    htmlspecialchars(
                        'Usuario:'
                    ),
                    ['underline' => 'single']
                );
                $textrun->addText(
                    htmlspecialchars(
                        ' '.$this->formularioResultado->getOrdenTrabajo()->getUser()->getUserName()
                    )
                );

                $this->section->addText('', [], ['borderBottomSize' => 6]);

                $textrun = $this->section->addTextRun();
              //comienza las descripcion del trabajo
                $textrun->addText(
                    htmlspecialchars(
                        'Descripción del trabajo'
                    )
                );

                // Buscar si hay incidencias
                $analisisInciencia = $ordenTrabajo->obtenerIncidencias();
                if (count($analisisInciencia['incidenciasEncontradas']) > 0) {
                    $this->section->addText('');
                    $tablaIncidencia = $this->section->addTable($this->table_style);
                    $tablaIncidencia->addRow();
                    $row = $tablaIncidencia->addCell(2000, $this->table_style_item);

                    $incidenciaTitulo = sprintf(
                        "%s %u/%u",
                        $this->get(TranslatorInterface::class)->trans('formulario_resultado_incidencias_encontradas', [
                            'encontradas' => count($analisisInciencia['incidenciasEncontradas'])
                        ]),
                        count($analisisInciencia['incidenciasEncontradas']),
                        $analisisInciencia['incidenciasTotal']
                    );
                    $row->addText(
                        htmlspecialchars($incidenciaTitulo),
                        ['size' => '12']
                    );
                    foreach ($analisisInciencia['incidenciasEncontradas']  as $incidencia) {
                        $tablaIncidencia->addRow();

                        $row = $tablaIncidencia->addCell(2000, $this->table_style_item);
                        $row->addText(htmlspecialchars($incidencia['item']), ['size' => '8']);

                        $opciones = (isset($incidencia['opciones']))
                            ? $incidencia['opciones']
                            : $this->get(TranslatorInterface::class)->trans('ningun_valor');
                        $row = $tablaIncidencia->addCell(2000, $this->table_style_item);
                        $row->addText(htmlspecialchars($opciones), ['size' => '8']);
                    }
                    $this->section->addText('');
                }


                //creo array de Resultados, agregado como indice idModulo, este tiene un array con indiceModulo y
                //cada array indice modulo tiene los resultado
                $resultados = [];
                foreach ($this->formularioResultado->getResultados() as $resultado) {
                    $resultados[$resultado->getPropiedadItem()->getModulo()->getId()][$resultado->getIndiceModulo()][$resultado->getIndiceModulo()][$resultado->getPropiedadItem()->getId()][] = $resultado;
                }
                $this->resultados = $resultados;

                //recorro propiedad modulos del formulario
                $contador = 1;
                $this->modulosRepetido = [];
                $paginaNombre = null;
                $paginaNumero = null;
                $mostrarPaginaNombre = false;



                foreach ($this->formularioResultado->getOrdenTrabajo()->getFormulario()->getPropiedadModulos() as $propiedadModulo) {
                    if (isset($this->resultados[$propiedadModulo->getModulo()->getId()])) {
                        if ($propiedadModulo->getPagina() != $paginaNumero) {
                            $paginaNumero = $propiedadModulo->getPagina();
                            $paginaNombre = $propiedadModulo->getPaginaNombre();
                            $mostrarPaginaNombre = true;
                        }

                        if (isset($this->modulosRepetido[$propiedadModulo->getModulo()->getId()])) {
                            ++$this->modulosRepetido[$propiedadModulo->getModulo()->getId()];
                        } else {
                            $this->modulosRepetido[$propiedadModulo->getModulo()->getId()] = 0;
                        }

                        //recorrer la cantidad de veces que tengo repetido modulo en resultado
                        $indice = $this->modulosRepetido[$propiedadModulo->getModulo()->getId()];

                        if (isset($this->resultados[$propiedadModulo->getModulo()->getId()][$indice])) {
                            if (count($this->resultados[$propiedadModulo->getModulo()->getId()][$indice]) > 0) {
                                if ($mostrarPaginaNombre) {
                                    $this->section->addTextBreak(1);

                                    $this->section->addText(
                                        htmlspecialchars(
                                            $paginaNombre
                                        ),
                                        ['bold' => true]
                                    );
                                    $mostrarPaginaNombre = false;
                                    $this->section->addTextBreak(1);
                                }
                                $this->section->addText(
                                    htmlspecialchars(
                                        $propiedadModulo->getModulo()->getTitulo()
                                    )
                                );
                                foreach ($this->resultados[$propiedadModulo->getModulo()->getId()][$indice] as $propiedadItems) {
                                    $this->renderModulo($propiedadModulo, $propiedadItems);
                                }
                            }
                        }
                        ++$contador;
                    }
                }

                if (!empty($ordenTrabajo->getImageName())) {
                    $this->renderFirmar($ordenTrabajo);
                }

                // Saving the document as OOXML file...
                $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

                $cliente = ($this->formularioResultado->getOrdenTrabajo()->getCliente())
                    ? $this->formularioResultado->getOrdenTrabajo()->getCliente()->getId() : '';
                $titulo = $this->slugify($ordenTrabajo->getFormulario()->getTitulo());
                $fileName = $this->formularioResultado->getOrdenTrabajo()->getId().'-'.$titulo.'-'.$cliente;

                $tmp = $this->createDirectory();


            }//fin foreeach

            if ($sucursal && !empty($sucursal->getImagePie())) {
                $footer->addImage(
                     $this->getParameter('kernel.root_dir').'/../public'.$this->get('vich_uploader.templating.helper.uploader_helper')->asset($ordenTrabajo->getSucursal(), 'imagePieFile'),
                     ['width' => 550, 'align' => 'center']
                 );
             } else {
                 $footer->addImage(
                     $this->getParameter('kernel.root_dir').'/../public/images/hogar_pie.png',
                     ['width' => 550, 'align' => 'center']
                 );
            }
            if ('WORD' == $this->formato) {
                $fileName .= '.doc';
                $contentType = 'application/msword';
                $objWriter->save($tmp.'/'.$fileName);
            } elseif ('PDF' == $this->formato) {
                //NUEVA FORMA DE CREAR PDF
                /*$archivo_doc = $tmp . "/" . $fileName.".doc";
                $archivo_pdf = $tmp . "/".$fileName.".pdf";
                $objWriter->save($archivo_doc);
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $path_dompdf = $this->container->getParameter("kernel.root_dir") . '/../vendor/dompdf/dompdf';
                \PhpOffice\PhpWord\Settings::setPdfRendererPath($path_dompdf);
                \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

                $phpWord = \PhpOffice\PhpWord\IOFactory::load($archivo_doc);
                $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
                $xmlWriter->save($archivo_pdf); // Save to PDF
                // unlink($archivo_doc);
                $fileName .= ".pdf";*/

                $convertidor = $this->getParameter('kernel.root_dir').'/Library/DC.py';
                $objWriter->save($tmp.'/'.$fileName.'.doc');
                $contentType = 'application/pdf';

                $archivo_docx = $tmp.'/'.$fileName.'.doc';

                $archivo_pdf = $tmp.'/'.$fileName.'.pdf';

                $command = $convertidor.' '.$archivo_docx.' '.$archivo_pdf;

                $process = new Process('/usr/bin/python3 '.$command);

                $process->run();

                $fileName .= '.pdf';
            }



        }elseif ('EXCEL' == $this->formato){
            $tmp = $this->createDirectory();
            $contentType = 'application/vnd.ms-excel';
            $fileName = $this->exportarExcel($tmp);
        }//fin if control pdf


        //$contentType = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
        $response = new BinaryFileResponse($tmp.'/'.$fileName);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        );
        $response->headers->set('Content-Type', $contentType);

        return $response;


    }//fin funcion exportar

    public function exportarExcel($tmp)
    {


        foreach ($this->ordenExcel as $valor){

            $ordenTrabajo = $this->em->getRepository(OrdenTrabajo::class)->find($valor);
            $this->formularioResultado = $ordenTrabajo->getFormularioResultado();



            $cliente = ($this->formularioResultado->getOrdenTrabajo()->getCliente())
                ? $this->formularioResultado->getOrdenTrabajo()->getCliente()->getId() : '';
            $titulo = $this->slugify($ordenTrabajo->getFormulario()->getTitulo());
            $fileName = $this->formularioResultado->getOrdenTrabajo()->getId().'-'.$titulo.'-'.$cliente.'.xls';

            //$spreadsheet = new Spreadsheet();
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->getParameter('kernel.root_dir').'/../public/uploads/templates/'.'template.xls');
            $sheet = $spreadsheet->getActiveSheet();

            foreach(range('B','G') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            $sheet->setCellValue('B2', $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.cliente'));
            $sheet->setCellValue('C2', $ordenTrabajo->getCliente());
            $sheet->setCellValue('B3', $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.direccion'));
            $sheet->setCellValue('C3', $ordenTrabajo->getCliente()->getStreet());
            $sheet->setCellValue('B4', $this->get(TranslatorInterface::class)->trans('ot.exportar.wordpdf.correo'));
            $sheet->setCellValue('C4', $ordenTrabajo->getCliente()->getCorreo());
            $sheet->setCellValue('B5', $this->get(TranslatorInterface::class)->trans('Fecha de envío del formulario'));
            $sheet->setCellValue('C5', $this->formularioResultado->getCreatedAt()->format('d/m/Y H:i'));
            $sheet->setCellValue('B6', $this->get(TranslatorInterface::class)->trans('Estado'));
            $sheet->setCellValue('C6', $ordenTrabajo->estadoToString());
            $i = 7;
            if (5 == $this->formularioResultado->getOrdenTrabajo()->getEstado()) {
                $sheet->setCellValue('B'.$i, 'Motivo');
                $sheet->setCellValue('C'.$i, $ordenTrabajo->getMotivo());
                $i++;
            }


            $horaInicio = ($ordenTrabajo->getHoraInicio())
                ? $ordenTrabajo->getHoraInicio()->format('H:i') : '';

            $horaFin = ($ordenTrabajo->getHoraFin())
                ? $ordenTrabajo->getHoraFin()->format('H:i') : '';
            $sheet->setCellValue('B'.$i, 'Hora Inicio - Fin');
            $sheet->setCellValue('C'.$i, ' '.$horaInicio.' - '.$horaFin); $i++;
            $sheet->setCellValue('B'.$i, 'Minutos Trabajados');
            $sheet->setCellValue('C'.$i, $this->formularioResultado->getMinutosTrabajado()); $i++;
            $sheet->setCellValue('B'.$i, 'Usuario');
            $sheet->setCellValue('C'.$i, $ordenTrabajo->getUser()->getUserName()); $i++;
            $sheet->setCellValue('B'.$i, 'Descripción del trabajo');
            // Buscar si hay incidencias
            $analisisInciencia = $ordenTrabajo->obtenerIncidencias();
            if (count($analisisInciencia['incidenciasEncontradas']) > 0) {

                $incidenciaTitulo = sprintf(
                    "%s %u/%u",
                    $this->get(TranslatorInterface::class)->trans('formulario_resultado_incidencias_encontradas', [
                        'encontradas' => count($analisisInciencia['incidenciasEncontradas'])
                    ]),
                    count($analisisInciencia['incidenciasEncontradas']),
                    $analisisInciencia['incidenciasTotal']
                );
                $i++;
                $sheet->setCellValue('C'.$i, $incidenciaTitulo);
                foreach ($analisisInciencia['incidenciasEncontradas']  as $incidencia) {
                    $i++;
                    $sheet->setCellValue('D'.$i, $incidencia['item']);

                    $opciones = (isset($incidencia['opciones']))
                        ? $incidencia['opciones']
                        : $this->get(TranslatorInterface::class)->trans('ningun_valor');
                    $i++;
                    $sheet->setCellValue('E'.$i, $opciones);
                    $i++;
                }
            }

            //creo array de Resultados, agregado como indice idModulo, este tiene un array con indiceModulo y
            //cada array indice modulo tiene los resultado
            $resultados = [];
            foreach ($this->formularioResultado->getResultados() as $resultado) {
                $resultados[$resultado->getPropiedadItem()->getModulo()->getId()][$resultado->getIndiceModulo()][$resultado->getIndiceModulo()][$resultado->getPropiedadItem()->getId()][] = $resultado;
            }
            $this->resultados = $resultados;

            //recorro propiedad modulos del formulario
            $contador = 1;
            $this->modulosRepetido = [];
            $paginaNombre = null;
            $paginaNumero = null;
            $mostrarPaginaNombre = false;

            foreach ($this->formularioResultado->getOrdenTrabajo()->getFormulario()->getPropiedadModulos() as $propiedadModulo) {
                if (isset($this->resultados[$propiedadModulo->getModulo()->getId()])) {
                    if ($propiedadModulo->getPagina() != $paginaNumero) {
                        $paginaNumero = $propiedadModulo->getPagina();
                        $paginaNombre = $propiedadModulo->getPaginaNombre();
                        $mostrarPaginaNombre = true;
                    }

                    if (isset($this->modulosRepetido[$propiedadModulo->getModulo()->getId()])) {
                        ++$this->modulosRepetido[$propiedadModulo->getModulo()->getId()];
                    } else {
                        $this->modulosRepetido[$propiedadModulo->getModulo()->getId()] = 0;
                    }

                    //recorrer la cantidad de veces que tengo repetido modulo en resultado
                    $indice = $this->modulosRepetido[$propiedadModulo->getModulo()->getId()];

                    if (isset($this->resultados[$propiedadModulo->getModulo()->getId()][$indice])) {
                        if (count($this->resultados[$propiedadModulo->getModulo()->getId()][$indice]) > 0) {
                            $sheet->setCellValue('B'.$i, $propiedadModulo->getModulo()->getTitulo());
                            foreach ($this->resultados[$propiedadModulo->getModulo()->getId()][$indice] as $propiedadItems) {
                                $i = $this->renderModuloExcel($propiedadModulo, $propiedadItems, $i, $sheet);
                            }
                        }
                    }
                    ++$contador;
                }
            }


        }


        $writer = new Xlsx($spreadsheet);

        $writer->save($tmp.'/'.$fileName);

        return $fileName;
    }

    private function renderModulo($propiedadModulo, $propiedadItemsResultado)
    {
        //recorro los item del modulo
        foreach ($propiedadModulo->getModulo()->getPropiedadItems() as $keyPi => $propiedadItem) {
            $propiedadItemsResultadoArray = (isset($propiedadItemsResultado[$propiedadItem->getId()])) ? $propiedadItemsResultado[$propiedadItem->getId()] : [];
            if (!empty($propiedadItemsResultadoArray || 'titulo' == $propiedadItem->getItem()->getTipo())) {
                $tablaModulo = $this->section->addTable($this->table_style);
                $this->renderItem($propiedadItem->getItem(), $propiedadItemsResultadoArray, $tablaModulo);
            }
        }
    }

    private function renderModuloExcel($propiedadModulo, $propiedadItemsResultado, $i, $sheet)
    {
        //recorro los item del modulo
        foreach ($propiedadModulo->getModulo()->getPropiedadItems() as $keyPi => $propiedadItem) {
            $propiedadItemsResultadoArray = (isset($propiedadItemsResultado[$propiedadItem->getId()])) ? $propiedadItemsResultado[$propiedadItem->getId()] : [];
            if (!empty($propiedadItemsResultadoArray || 'titulo' == $propiedadItem->getItem()->getTipo())) {
                $i = $this->renderItemExcel($propiedadItem->getItem(), $propiedadItemsResultadoArray, $i, $sheet);
            }
        }

        return $i;
    }

    private function renderItemExcel($item, $resultados, $i, $sheet)
    {
        //si es de tipo foto
        if ('foto' == $item->getTipo()) {
            //obtengo todos los resultados de propiedad item

            $mostrarTitulo = true;
            foreach ($resultados as $keyResultado => $resultado) {
                if (!empty($resultado->getImageName())) {
                    if ($mostrarTitulo) {
                        $sheet->setCellValue('C'.$i, $item->getTitulo());
                        $mostrarTitulo = false;
                    }
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($this->getParameter('kernel.root_dir').'/../public'.$this->get('vich_uploader.templating.helper.uploader_helper')->asset($resultado, 'imageFile'));
                    $drawing->setCoordinates('D'.$i);
                    $drawing->setResizeProportional(true);
                    $drawing->setHeight(140);
                    $drawing->setWorksheet($sheet);
                    $sheet->getStyle('D'.$i)->getAlignment()->setWrapText(true);
                    $sheet->getRowDimension($i)->setRowHeight(140);
                    $i++;
                }
            }
        } else {
            if ('titulo' == $item->getTipo()) {
                $sheet->setCellValue('C'.$i, $item->getTitulo());
                $i++;
            } else {
                $registro = '';
                $cantidadResultados = count($resultados) - 1;
                foreach ($resultados as $keyResultado => $resultado) {
                    $registro .= $resultado->obtenerValorToString();
                    $registro .= ($cantidadResultados == $keyResultado) ? '' : ' | ';
                }
                if (!empty($registro)) {
                    $sheet->setCellValue('C'.$i, $item->getTitulo());
                    $sheet->setCellValue('D'.$i, $registro);
                    $i++;
                }
            }
        }
        return $i;
    }

    private function renderItem($item, $resultados, $tablaModulo)
    {
        //si es de tipo foto
        if ('foto' == $item->getTipo()) {
            //obtengo todos los resultados de propiedad item

            $mostrarTitulo = true;
            foreach ($resultados as $keyResultado => $resultado) {
                if (!empty($resultado->getImageName())) {
                    if ($mostrarTitulo) {
                        $tablaModulo->addRow();
                        $row = $tablaModulo->addCell(1000, $this->table_style_item);
                        $row->addText(htmlspecialchars($item->getTitulo()), ['size' => '8']);
                        $tablaModulo->addRow();
                        $row = $tablaModulo->addCell(1000, $this->table_style_item);
                        $textrun = $row->createTextRun();
                        $mostrarTitulo = false;
                    }
                    //imagen foto
                    $textrun->addImage(
                        $this->getParameter('kernel.root_dir').'/../public'.$this->get('vich_uploader.templating.helper.uploader_helper')->asset($resultado, 'imageFile'),
                        ['wrappingStyle' => 'behind', 'width' => 70, 'height' => 100, 'align' => 'left']
                    );

                    $textrun->addText(htmlspecialchars('  '), ['size' => '8']);
                }
            }
        } else {
            if ('titulo' == $item->getTipo()) {
                $tablaModulo->addRow();
                $registro = $item->getTitulo();
                $font = ['bold' => true];
                $tablaModulo->addCell(1000, $this->table_style_item)->addText(htmlspecialchars($registro), ['size' => '8'], $font);
            } else {
                $registro = '';
                $cantidadResultados = count($resultados) - 1;
                foreach ($resultados as $keyResultado => $resultado) {
                    $registro .= $resultado->obtenerValorToString();
                    $registro .= ($cantidadResultados == $keyResultado) ? '' : ' | ';
                }
                if (!empty($registro)) {
                    $tablaModulo->addRow();
                    $tablaModulo->addCell(600, $this->table_style_item)->addText(htmlspecialchars($item->getTitulo().': '), ['size' => '8']);
                    $tablaModulo->addCell(400, $this->table_style_item)->addText(htmlspecialchars($registro), ['size' => '8'], ['align' => 'center']);
                }
            }
        }
    }

    private function renderFirmar($ordenTrabajo)
    {
        $tablaFirma = $this->section->addTable($this->table_style_titulo);
        $tablaFirma->addRow();

        $tablaFirma->addCell(2000)->addText(
            htmlspecialchars(
                'Firma'
            ), [], ['align' => 'center']
        );

        $tablaFirma->addRow();

        $tablaFirma->addCell(2000)->addImage(
            $this->getParameter('kernel.root_dir').'/../public'.$this->get('vich_uploader.templating.helper.uploader_helper')->asset($ordenTrabajo, 'imageFile'),
            ['wrappingStyle' => 'behind', 'width' => 100, 'height' => 100, 'align' => 'center']
        );

        $tablaFirma->addRow();

        $tablaFirma->addCell(2000)->addText(
            htmlspecialchars(
                $ordenTrabajo->getResponsableFirma()
            ), [], ['align' => 'center']
        );
    }

    private function createDirectory()
    {
//        $folderUser = (!$this->isGranted('ROLE_SUPER_ADMIN')) ? $this->getUser()->getId() : 'superadmin';
          $folderUser = 'superadmin';
          $filepath = $this->getParameter('kernel.root_dir').'/../public/uploads/tmp/'.$folderUser;

        if (!file_exists($filepath)) {
            $filepath = $this->getParameter('kernel.root_dir').'/../public/uploads/';

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }

            $filepath .= 'tmp/';

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }

            $filepath .= $folderUser;

            if (!file_exists($filepath)) {
                mkdir($filepath);
            }
        }

        return $filepath;
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    protected function persistEntity($entity)
    {
        // if ($this->isGranted('ROLE_ADMIN')) {
        //     $entity->setSucursal($entity->getCliente()->getSucursal());
        // }

        if ($this->request->getSession()->get('solicitud_ot', null)) {
            $solicitud = $this->em->getRepository(Solicitud::class)
                ->find($this->request->getSession()->get('solicitud_ot')['id']);
            if ($solicitud->getOrdenTrabajo()) {
                $this->addFlash('warning', 'La solicitud ya tiene una orden de trabajo generada');

                return $this->redirect($this->generateUrl(
                    'easyadmin', [ 'action' => 'list', 'entity' => $this->entity['name'] ]
                ));
            }
            $entity->setCliente($solicitud->getCliente());
            $entity->setComentario($solicitud->getDetalle());
            $entity->setSolicitud($solicitud);

            $this->request->getSession()->remove('solicitud_ot');
        }

        parent::persistEntity($entity);
    }

    protected function updateEntity($entity)
    {
        // Fijarse si clickeo desvincular ot con solicitud
        if ($this->request->request->get('ordentrabajo', null) && '1' == $this->request->request->get('ordentrabajo')['solicitudOpcion']) {
            $entity->getSolicitud()->setOrdenTrabajo(null);
        }

        // if ($this->isGranted('ROLE_ADMIN')) {
        //     $entity->setSucursal($entity->getCliente()->getSucursal());
        // }
        parent::updateEntity($entity);
    }

    protected function newAction()
    {
        if (
            (
                $this->request->request->get('ordentrabajo', null)
                && '1' == $this->request->request->get('ordentrabajo')['solicitudOpcion']
            )
            ||
            (
                $this->request->query->get('from_list', null)
                && '1' == $this->request->query->get('from_list')
            )
        ) {
            $this->request->getSession()->remove('solicitud_ot');
        }

        return parent::newAction();
    }

    protected function createNewEntity()
    {
        $entity = parent::createNewEntity();

        if ($this->request->getSession()->get('solicitud_ot', null)) {
            $solicitud = $this->em->getRepository(Solicitud::class)
                ->find($this->request->getSession()->get('solicitud_ot')['id']);
            $solicitud->setEstado(1);
            $entity->setCliente($solicitud->getCliente());
            $entity->setComentario($solicitud->getDetalle());
        }

        return $entity;
    }

    public function cambiarEstadoGestionAction()
    {
        $newValue = $this->request->query->get('newValue');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $this->updateEntityProperty($easyadmin['item'], 'estadoGestion', $newValue);

        // cast to integer instead of string to avoid sending empty responses for 'false'
        return new Response((int) $newValue);
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listAction()
    {
        if ($this->isGranted('ROLE_CLIENTE') && !$this->isGranted('ROLE_ADMIN') && !$this->request->query->has('entity')) {
            return $this->redirectToRoute('easyadmin', array(
                'entity'    => 'OrdenTrabajoCliente',
                'action'    => 'list',
            ));
        }elseif($this->isGranted('ROLE_CLIENTE') && !$this->isGranted('ROLE_ADMIN') && $this->request->query->get('entity')=='OrdenTrabajo'){
            return $this->redirectToRoute('easyadmin', array(
                'entity'    => 'OrdenTrabajoCliente',
                'action'    => 'list',
            ));
        }
        
        return parent::listAction(); 
    }



}
