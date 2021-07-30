<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserConstraint extends Constraint
{
    public $campoClienteObligatorio = 'El campo cliente es obligatorio';
    public $campoSucursalObligatorio = 'El campo sucursal es obligatorio';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
