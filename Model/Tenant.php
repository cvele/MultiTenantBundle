<?php

namespace Cvele\MultiTenantBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Tenant implements TenantInterface
{
    protected $id;

    protected $name;

    protected $users;

    protected $owner;

	/**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tenant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param TenantAwareUserInterface $user
     */
    public function addUser(TenantAwareUserInterface $user)
    {
        if ($this->users->contains($user)) {
            return;
        }
        $this->users->add($user);
        $user->addUserTenant($this);
    }

    /**
     * @param TenantAwareUserInterface $user
     */
    public function removeUser(TenantAwareUserInterface $user)
    {
        if (!$this->users->contains($user)) {
            return;
        }
        $this->users->removeElement($user);
        $user->removeUserTenant($this);
    }

    /**
     * Gets the value of users.
     *
     * @return \Doctrine\Common\Collections\Collection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Gets the value of owner.
     *
     * @return mixed
     */
    // public function getOwner()
    // {
    //     return $this->owner;
    // }

    /**
     * Sets the value of owner.
     *
     * @param mixed $owner the owner
     *
     * @return self
     */
    // public function setOwner($owner)
    // {
    //     $this->owner = $owner;

    //     return $this;
    // }

    public function __toString()
    {
        return (string) $this->getName();
    }
}
