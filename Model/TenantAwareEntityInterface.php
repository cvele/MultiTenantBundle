<?php

namespace Cvele\MultiTenantBundle\Model;

use Cvele\MultiTenantBundle\Model\TenantInterface;

/**
 * @author Vladimir Cvetic <vladimir@ferdinand.rs>
 */
interface TenantAwareEntityInterface
{
    public function getTenant();

    public function setTenant(TenantInterface $tenant);
}
