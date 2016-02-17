<?php

namespace Cvele\MultiTenantBundle\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class TenantAware
{
    public $tenant;
}
