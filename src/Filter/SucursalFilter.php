<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use App\Entity\iSucursalFilter;

class SucursalFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(iSucursalFilter::class)) {
            return '';
        }

        return $targetTableAlias.'.sucursal_id in ('.$this->getParameter('sucursal_id').')';

    }
}
