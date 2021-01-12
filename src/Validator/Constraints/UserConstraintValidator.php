<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UserConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (in_array('ROLE_CLIENTE', $value->getRoles()) && !$value->getSucursal()) {
            // Si es rol cliente el campo sucursal debe ser requerido
            $this->context->buildViolation($constraint->campoSucursalObligatorio)
                ->atPath('sucursal')
                ->addViolation();
        }
        if (in_array('ROLE_CLIENTE', $value->getRoles()) && !$value->getCliente() ) {
            // Si es rol cliente el campo cliente debe ser requerido
            $this->context->buildViolation($constraint->campoClienteObligatorio)
                ->atPath('cliente')
                ->addViolation();
        }
    }
}
