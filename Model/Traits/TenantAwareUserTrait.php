<?php

namespace Cvele\MultiTenantBundle\Model\Traits;

use Cvele\MultiTenantBundle\Model\TenantInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
trait TenantAwareUserTrait
{
    protected $userTenants;

	public function setupTenantAwareUserTrait()
	{
		$this->userTenants = new ArrayCollection();
	}

    public function getTenant()
    {
        return $this->userTenants[0];
    }

	/**
	 * @return ArrayCollection
	 */
    public function getUserTenants()
    {
        return $this->userTenants;
    }

    /**
     * @param Tenant $tenant
     *
     * @return self
     */
    public function addUserTenant(TenantInterface $tenant)
    {
        if ($this->userTenants->contains($tenant)) {
            return;
        }
        $this->userTenants->add($tenant);
        $tenant->addUser($this);
        return $this;
    }
    /**
     * @param Tenant $tenant
     *
     * @return self
     */
    public function removeUserTenant(TenantInterface $tenant)
    {
        if (!$this->userTenants->contains($tenant)) {
            return;
        }
        $this->userTenants->removeElement($tenant);
        $tenant->removeUser($this);
        return $this;
    }
}