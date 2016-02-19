<?php

namespace Cvele\MultiTenantBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Cvele\MultiTenantBundle\Model\TenantInterface;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
class TenantManager
{
    protected $objectManager;
    protected $class;
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager           $om
     * @param string                  $class
     */
    public function __construct(ObjectManager $om, $class)
    {
  		$this->objectManager = $om;
  		$this->repository    = $om->getRepository($class);

  		$metadata    = $om->getClassMetadata($class);
  		$this->class = $metadata->getName();
    }

    /**
     * Returns an empty tenant instance
     *
     * @return UserInterface
     */
    public function createTenant()
    {
        $class = $this->getClass();
        $tenant = new $class;

        return $tenant;
    }

    /**
     * Finds a tenant by name
     *
     * @param string $name
     *
     * @return TenantInterface
     */
    public function findTenantByName($name)
    {
        return $this->findTenantBy(['name' => $name]);
    }

    /**
     * Finds a tenant by id
     *
     * @param integer $id
     *
     * @return TenantInterface
     */
    public function findTenantById($id)
    {
        return $this->findTenantBy(['id' => $id]);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteTenant(TenantInterface $tenant)
    {
        $this->objectManager->remove($tenant);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findTenantBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findTenants()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function reloadTenant(TenantInterface $tenant)
    {
        $this->objectManager->refresh($tenant);
    }

    /**
     * Updates a tenant.
     *
     * @param TenantInterface $tenant
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateTenant(TenantInterface $tenant, $andFlush = true)
    {
        $this->objectManager->persist($tenant);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}
