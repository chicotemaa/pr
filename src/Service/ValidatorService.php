<?php

namespace App\Service;

use App\Entity\Solicitud;
use DateTime;

class ValidatorService
{
    public function isFormEquipoClienteValid($entity)
    {
        $modulos = $entity->getFormulario()->getPropiedadModulos();
        $return = true;
        foreach($modulos as $modulo) {
            if ($modulo->getEquipo()->getCliente() <> $entity->getCliente()) {
                $return = false;
            }
        }

        return $return;
    }
}
