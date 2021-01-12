<?php

namespace App\Service;

use App\Entity\Solicitud;
use DateTime;

class MailReader
{
    protected $hostname;
    protected $user;
    protected $password;

    public function __construct($hostname, $user, $password)
    {
        $this->hostname = $hostname;
        $this->user = $user;
        $this->password = $password;
    }

    public function read()
    {
        $inbox = imap_open($this->hostname, $this->user, $this->password) or die('No se puede conectar con el servidor de mails: '.imap_last_error());

        $emails = imap_search($inbox, 'BODY "One Solution"');
        $solicitudes = [];
        $incidenciaTexto = utf8_decode('Número de incidencia:');
        $ayudaTexto = utf8_decode('En qué necesitás ayuda?:');
        $problemaTexto = utf8_decode('Cual es el problema?:');
        $numsucTexto = utf8_decode('Número Sucursal:');
        $dicsucTexto = utf8_decode('Dirección Sucursal:');
        $pisosectorTexto = utf8_decode('Piso/Sector afectado:');
        $detalleTexto = utf8_decode('Detalle:');
        $fechaTexto = utf8_decode('Fecha de compromiso de solución:');
        if ($emails) {
            rsort($emails); //ordeno para ver los nuevos
            for ($i = 0; $i < 20; ++$i) {
                $inc = '';
                $ayu = '';
                $pro = '';
                $nsuc = '';
                $ds = '';
                $ps = '';
                $det = '';
                $fec = '';
                /* informacion del mail */
                //$overview = imap_fetch_overview($inbox, $email_number, 0);
                if (array_key_exists($i, $emails)) {
                    $message = quoted_printable_decode(imap_fetchbody($inbox, $emails[$i], 1.1, FT_PEEK));
                    $message = utf8_decode(base64_decode($message));
                    $cadena = explode("\n", $message); //separo por saltos de linea
                    foreach ($cadena as $linea) {
                        $incidencia = strpos($linea, $incidenciaTexto); //busco si esta el detalle;
                        $ayuda = strpos($linea, $ayudaTexto);
                        $problema = strpos($linea, $problemaTexto);
                        $numsuc = strpos($linea, $numsucTexto);
                        $dicsuc = strpos($linea, $dicsucTexto);
                        $pisosector = strpos($linea, $pisosectorTexto);
                        $detalle = strpos($linea, $detalleTexto);
                        $fecha = strpos($linea, $fechaTexto);
                        if (false === $incidencia) {
                        } else {
                            $inc = str_replace($incidenciaTexto, '', $linea);
                            $inc = str_replace('*', '', $inc);
                            $inc = trim($inc);
                        }
                        if (false === $ayuda) {
                        } else {
                            $ayu = str_replace($ayudaTexto, '', $linea);
                            $ayu = trim($ayu);
                        }
                        if (false === $problema) {
                        } else {
                            $pro = str_replace($problemaTexto, '', $linea);
                            $pro = trim($pro);
                        }
                        if (false === $numsuc) {
                        } else {
                            $nsuc = str_replace($numsucTexto, '', $linea);
                            $nsuc = trim($nsuc);
                        }
                        if (false === $dicsuc) {
                        } else {
                            $ds = str_replace($dicsucTexto, '', $linea);
                            $ds = trim($ds);
                        }
                        if (false === $pisosector) {
                        } else {
                            $ps = str_replace($pisosectorTexto, '', $linea);
                            $ps = trim($ps);
                        }
                        if (false === $detalle) {
                        } else {
                            $det = str_replace($detalleTexto, '', $linea);
                            $det = trim($det);
                        }
                        if (false === $fecha) {
                        } else {
                            $fec = str_replace($fechaTexto, '', $linea);
                            $fec = str_replace('*', '', $fec);
                            $fec = trim($fec);
                            $fec = DateTime::createFromFormat('d/m/Y H:i:s a', $fec);
                        }
                    }
                    if ('' != $inc && '' != $ayu && '' != $pro && '' != $nsuc && '' != $ds && '' != $ps && '' != $det && '' != $fec) {
                        $solicitud = new Solicitud();
                        $solicitud->setConsulta(utf8_encode($pro));
                        $solicitud->setDetalle(utf8_encode($det));
                        $solicitud->setNroIncidencia(utf8_encode($inc));
                        $solicitud->setNumeroSucursal(utf8_encode($nsuc));
                        $solicitud->setDireccionSucursal(utf8_encode($ds));
                        $solicitud->setPisoSector(utf8_encode($ps));
                        $solicitud->setNecesitasAyuda(utf8_encode($ayu));
                        $solicitud->setFechaCompromiso($fec);
                        array_push($solicitudes, $solicitud);
                    }
                }
            }
        }
        imap_close($inbox);
        return $solicitudes;
    }
}
