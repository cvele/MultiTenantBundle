<?php

namespace Cvele\MultiTenantBundle\Tests;

use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;
use Cvele\MultiTenantBundle\Model\Traits\TenantAwareUserTrait;

class TestUser implements TenantAwareUserInterface
{
  use TenantAwareUserTrait;

  public function __construct()
  {
    $this->setupTenantAwareUserTrait();
  }

  public function setId($id)
  {
      $this->id = $id;
  }
}
