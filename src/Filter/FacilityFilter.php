<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use App\Entity\iFacilityFilter;

class FacilityFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(iFacilityFilter::class)) {
            return '';
        }
        //dump($targetTableAlias);die;
        return $targetTableAlias.'.cliente_sucursal_id = 2 or '. $targetTableAlias.'.cliente_sucursal_id = 1';
    }
}
