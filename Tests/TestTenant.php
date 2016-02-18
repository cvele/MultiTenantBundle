<?php

namespace Cvele\MultiTenantBundle\Tests;

use Cvele\MultiTenantBundle\Model\Tenant;

class TestTenant extends Tenant
{
    public function setId($id)
    {
        $this->id = $id;
    }
}
