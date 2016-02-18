<?php

namespace Cvele\MultiTenantBundle\Tests\Model;

use Cvele\MultiTenantBundle\Model\Tenant;
use Doctrine\Common\Collections\ArrayCollection;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testTenant()
    {
        $tenant = $this->getTenant();
        $entity = $this->getEntity();
        $this->assertNull($entity->getTenant());

        $entity->setTenant($tenant);
        $this->assertEquals($tenant, $entity->getTenant());
    }
    
    protected function getEntity()
    {
      return $this->getMockForAbstractClass('Cvele\MultiTenantBundle\Tests\TestEntity');
    }

    /**
     * @return Tenant
     */
    protected function getTenant()
    {
        return $this->getMockForAbstractClass('Cvele\MultiTenantBundle\Model\Tenant');
    }
}
