<?php

namespace Cvele\MultiTenantBundle\Event\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class DynamicRelationSubscriber implements EventSubscriber
{
	public function __construct($tenantClass, $userClass)
	{
		$this->tenantClass = $tenantClass;
		$this->userClass   = $userClass;
	}

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        if ($metadata->getName() != $this->tenantClass) {
            return;
        }

        $metadata->mapManyToMany(array(
            'targetEntity'  => $this->userClass,
            'fieldName'     => 'userTenants',
            'cascade'       => array('persist'),
            'joinTable'     => array(
                'name'        => 'tenant_users',
                'joinColumns' => array(
                    array(
                        'name'                  => 'user_id',
                        'referencedColumnName'  => 'id'
                    ),
                ),
                'inverseJoinColumns'    => array(
                    array(
                        'name'                  => 'tenant_id',
                        'referencedColumnName'  => 'id'
                    ),
                )
            )
        ));
    }
}
