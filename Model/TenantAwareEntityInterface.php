<?php

namespace Cvele\MultiTenantBundle\Model;

use Cvele\MultiTenantBundle\Model\TenantInterface;

/**
 * @author Vladimir Cvetic <vladimir@ferdinand.rs>
 */
interface TenantAwareEntityInterface
{
    public function getTenant();

    protected function setTenant(TenantInterface $tenant);
}
