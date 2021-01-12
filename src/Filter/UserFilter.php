<?php

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use App\Entity\iUserFilter;

class UserFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements the LocalAware interface
        if (!$targetEntity->reflClass->implementsInterface(iUserFilter::class)) {
            return '';
        }

        return $targetTableAlias.'.user_id = '.$this->getParameter('user_id');
    }
}
