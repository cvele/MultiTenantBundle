<?php

namespace Cvele\MultiTenantBundle\Model;

use Cvele\MultiTenantBundle\Model\TenantInterface;

/**
 * @author Vladimir Cvetic <vladimir@ferdinand.rs>
 */
interface TenantAwareEntityInterface
{
    function getTenant();

    function setTenant(TenantInterface $tenant);
}
