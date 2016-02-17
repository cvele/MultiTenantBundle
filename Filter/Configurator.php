<?php

namespace Cvele\MultiTenantBundle\Filter;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Annotations\Reader;
use Cvele\MultiTenantBundle\Model\TenantInterface;
use Cvele\MultiTenantBundle\Helper\TenantHelper;

class Configurator
{
    protected $em;
    protected $reader;
    protected $helper;

    public function __construct(ObjectManager $em, Reader $reader, TenantHelper $helper)
    {
        $this->em      = $em;
        $this->helper  = $helper;
        $this->reader  = $reader;
    }

    public function onKernelRequest()
    {
        $tenant = $this->helper->getCurrentTenant();
        if ($tenant instanceof TenantInterface) {
          try {
            $filter = $this->em->getFilters()->enable('tenant_filter');
          } catch (\InvalidArgumentException $e) {
            return;
          }
          $filter->setParameter('id', $tenant->getId());
          $filter->setAnnotationReader($this->reader);
        }
    }
}
