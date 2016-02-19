<?php

namespace Cvele\MultiTenantBundle\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface;
use Cvele\MultiTenantBundle\Model\TenantInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class EntityListener
{
  private $session;
  private $tenant_class;

  public function __construct(Session $session, $tenant_class)
  {
    $this->session = $session;
    $this->tenant_class = $tenant_class;
  }

  public function prePersist(LifecycleEventArgs $args)
  {
      $entity = $args->getEntity();

      if (!$entity instanceof TenantAwareEntityInterface) {
        return;
      }

      $tenantId = $this->session->get('tenant_id', null);
      if (empty($tenantId)) {
        return;
      }

      $entityManager = $args->getEntityManager();

      $tenant = $entityManager->getRepository($this->tenant_class)->findOneBy(['id' => $tenantId]);
      if ($tenant === null || !($tenant instanceof TenantInterface)) {
        return;
      }

      $entity->setTenant($tenant);
  }
}
