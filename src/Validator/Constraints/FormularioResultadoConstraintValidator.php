<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Annotation
 */
class FormularioResultadoConstraintValidator extends ConstraintValidator
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        $resultadoArray = $value->getOrdenTrabajo()->mergeResultadoFormulario();
        $camposRequeridoSinResultados = [];
        $modulosRepetidos = [];

        foreach ($value->getOrdenTrabajo()->getFormulario()->getPropiedadModulos() as $pm) {
            $modulosRepetidos = $value->getOrdenTrabajo()->arrayIndiceModulo($modulosRepetidos, $pm->getModulo()->getId());
            foreach ($pm->getModulo()->getPropiedadItems() as $pi) {
                if ($pi->getRequerido()) {
                    if (!isset($resultadoArray[$pm->getModulo()->getId()]) ||
                        !isset($resultadoArray[$pm->getModulo()->getId()][$modulosRepetidos[$pm->getModulo()->getId()]])) {
                        $camposRequeridoSinResultados[] = $pi->getItem()->__toString();
                    } elseif (0 == count($resultadoArray[$pm->getModulo()->getId()][$modulosRepetidos[$pm->getModulo()->getId()]])) {
                        $camposRequeridoSinResultados[] = $pi->getItem()->__toString();
                    }
                }
            }
        }

        if (count($camposRequeridoSinResultados) > 0) {
            $this->context->buildViolation(
                $this->translator->trans('formulario_resultado_errores', [
                    'errors' => count($camposRequeridoSinResultados),
                    'campos' => implode(',', $camposRequeridoSinResultados),
                ])
            )
            ->addViolation();
        }
    }
}
