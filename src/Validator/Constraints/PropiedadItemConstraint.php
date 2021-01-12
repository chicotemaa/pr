<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PropiedadItemConstraint extends Constraint
{
    public $messageErrorOpcionDepende = ' El campo opcion depende es requerida';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
