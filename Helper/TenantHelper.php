<?php

namespace Cvele\MultiTenantBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;
use Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface;

class TenantHelper
{
	/**
	 * @var Session
	 */
	protected $session;

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	public function isTenantObjectOwner($object)
	{
		if (!($object instanceof TenantAwareEntityInterface))
		{
			throw new \Exception("Object [%s] must implement Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface to be owned by Tenant");
		}

		return ($object->getTenant()->getId() === $this->session->get('tenant_id'));
	}
}