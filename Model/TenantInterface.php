<?php

namespace Cvele\MultiTenantBundle\Model;

use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
interface TenantInterface
{
	public function getId();

	public function setName($name);

	public function getName();

	public function addUser(TenantAwareUserInterface $user);

    public function removeUser(TenantAwareUserInterface $user);

    public function getUsers();

    public function getOwner();

    public function setOwner(TenantAwareUserInterface $user);
}
