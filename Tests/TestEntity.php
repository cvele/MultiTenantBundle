<?php

namespace Cvele\MultiTenantBundle\Tests;

use Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface;
use Cvele\MultiTenantBundle\Model\Traits\TenantAwareEntityTrait;

class TestEntity implements TenantAwareEntityInterface
{
  use TenantAwareEntityTrait;

  public function setId($id)
  {
      $this->id = $id;
  }
}
