<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PropiedadItemConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ($value->getDependePropiedadItem() && !$value->getOpcionDepende()) {
            $this->context->buildViolation($constraint->messageErrorOpcionDepende)
              ->atPath('item')
              ->addViolation();
        }
    }
}
