<?php

namespace Cvele\MultiTenantBundle\Exception;

class UserHasNoTenantsException extends \Exception
{
	protected $user;

	public function __construct($user)
	{
		$this->user;
	}

	public function getUser()
	{
		return $this->user;
	}
}