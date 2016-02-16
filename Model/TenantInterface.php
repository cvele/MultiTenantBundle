<?php

namespace Cvele\MultiTenantBundle\Model;

use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
interface TenantInterface
{
	function getId();

	function setName($name);

	function getName();

	function addUser(TenantAwareUserInterface $user);

    function removeUser(TenantAwareUserInterface $user);

    function getUsers();

    function getOwner();

    function setOwner(TenantAwareUserInterface $user);
}
