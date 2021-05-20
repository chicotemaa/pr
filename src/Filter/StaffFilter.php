<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use App\Entity\iStaffFilter;

class StaffFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(iStaffFilter::class)) {
            return '';
        }

        return $targetTableAlias.'.street = '.$this->getParameter('street');
    }
}