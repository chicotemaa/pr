<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FormularioResultadoConstraint extends Constraint
{
    public $messageErrorCamposRequeridos = 'Los {campos} son requeridos';
    public $messageErrorCampoRequerido = 'Los {campos} son requeridos';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
