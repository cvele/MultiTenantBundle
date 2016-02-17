<?php

namespace Cvele\MultiTenantBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class TenantAware
{
    public $tenant;
}
