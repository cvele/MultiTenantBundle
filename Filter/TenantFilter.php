<?php

namespace Cvele\MultiTenantBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\Reader;

class TenantFilter extends SQLFilter
{
    protected $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        $tenantAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            'Cvele\\MultiTenantBundle\\Annotation\\TenantAware'
        );

        if (!$tenantAware) {
            return '';
        }

        $fieldName = $tenantAware->tenant;

        try {
            $tenantId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            return '';
        }

        if (empty($fieldName) || empty($tenantId)) {
            return '';
        }

        $query = sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $tenantId);

        return $query;
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
