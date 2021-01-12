<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class SolicitudConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /*
            Si la solicitud esta por editarse y tiene ot no permitir editar o eliminar
        */
        if ($value->getId() && $value->getOrdenTrabajo()) {
            $this->context->buildViolation('No puede editarse, porque tiene una OT asociada')
                ->addViolation();
        }
    }
}
