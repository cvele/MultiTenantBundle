<?php

namespace Cvele\MultiTenantBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;
use Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface;
use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;
use Cvele\MultiTenantBundle\Model\TenantInterface;
use Cvele\MultiTenantBundle\Model\TenantManager;

class TenantHelper
{
	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var TenantManager
	 */
	public function __construct(Session $session, TenantManager $manager)
	{
		$this->session = $session;
		$this->manager = $manager;
	}

	public function getCurrentTenant()
	{
		$tenantId = $this->session->get('tenant_id', null);
		if (empty($tenantId)) {
			return null;
		}

		return $this->manager->findTenantBy(['id' => $tenantId]);
	}

	public function setCurrentTenant(TenantInterface $tenant)
	{
		$this->session->set('tenant_id', $tenant->getId());
	}

	public function isUserTenantOwner(TenantAwareUserInterface $user)
	{
		return ($object->getTenant()->getOwner() === $user);
	}

	public function isTenantObjectOwner($object)
	{
		if (!($object instanceof TenantAwareEntityInterface))
		{
			throw new \Exception("Object [%s] must implement Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface to be owned by Tenant");
		}

		return ($object->getTenant()->getId() === $this->session->get('tenant_id'));
	}

	public function addUserToTenant(TenantAwareUserInterface $user, TenantInterface $tenant)
	{
		$tenant->addUser($user);
		$this->manager->updateTenant($tenant);
	}

	public function removeUserFromTenant(TenantAwareUserInterface $user, TenantInterface $tenant)
	{
		$tenant->removeUser($user);
		$this->manager->updateTenant($tenant);
	}

	public function createTenant($name, TenantAwareUserInterface $owner)
	{
		$tenant = $this->manager->createTenant();
		$tenant->setName($name);
		$tenant->setOwner($owner);
		$this->manager->updateTenant($tenant);
		return $tenant;
	}
}
