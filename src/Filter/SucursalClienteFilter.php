<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use App\Entity\iSucursalClienteFilter;

class SucursalClienteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(iSucursalClienteFilter::class)) {
            return '';
        }

        return $targetTableAlias.'.sucursal_de_cliente_id = '.$this->getParameter('sucursal_de_cliente_id');
    }
}