<?php

namespace Cvele\MultiTenantBundle\Model\Traits;

use Cvele\MultiTenantBundle\Model\TenantInterface;

/**
 * @author Vladimir Cvetic <vladimir@ferdinand.rs>
 */
trait TenantAwareEntityTrait
{
    protected $tenant;

    /**
     * Gets the value of tenant.
     *
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Sets the value of tenant.
     *
     * @param TenantInterface $tenant the tenant
     *
     * @return self
     */
    protected function setTenant(TenantInterface $tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }
}
