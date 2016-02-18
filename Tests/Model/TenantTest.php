<?php

namespace Cvele\MultiTenantBundle\Tests\Model;

use Cvele\MultiTenantBundle\Model\Tenant;
use Doctrine\Common\Collections\ArrayCollection;

class TenantTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $tenant = $this->getTenant();
        $this->assertNull($tenant->getName());

        $tenant->setName('tenant');
        $this->assertEquals('tenant', $tenant->getName());
    }

    public function testOwner()
    {
        $tenant = $this->getTenant();
        $this->assertNull($tenant->getOwner());

        $user = $this->getUser();

        $tenant->setOwner($user);
        $this->assertEquals($user, $tenant->getOwner());
    }

    public function testAddUser()
    {
        $tenant = $this->getTenant();
        $this->assertNull($tenant->getOwner());

        $user = $this->getUser();

        $tenant->addUser($user);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $tenant->getUsers());
        $this->assertTrue($tenant->getUsers()->contains($user));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getUserTenants());
        $this->assertTrue($user->getUserTenants()->contains($tenant));
    }

    public function testRemoveUser()
    {
        $tenant = $this->getTenant();
        $this->assertNull($tenant->getOwner());

        $user = $this->getUser();

        $tenant->addUser($user);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $tenant->getUsers());
        $this->assertTrue($tenant->getUsers()->contains($user));

        $tenant->removeUser($user);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $tenant->getUsers());
        $this->assertFalse($tenant->getUsers()->contains($user));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getUserTenants());
        $this->assertFalse($user->getUserTenants()->contains($tenant));
    }

    protected function getUser()
    {
      return $this->getMockForAbstractClass('Cvele\MultiTenantBundle\Tests\TestUser');
    }

    /**
     * @return Tenant
     */
    protected function getTenant()
    {
        return $this->getMockForAbstractClass('Cvele\MultiTenantBundle\Model\Tenant');
    }
}
