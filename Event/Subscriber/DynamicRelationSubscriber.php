<?php

namespace Cvele\MultiTenantBundle\Event\Subscriber;

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
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        if ($metadata->getName() == $this->tenantClass) {
            $metadata->mapManyToMany([
                'targetEntity'  => $this->userClass,
                'fieldName'     => 'userTenants',
                'cascade'       => ['persist'],
                'joinTable'     => [
                    'name'        => 'tenant_users',
                    'joinColumns' => [
                        [
                            'name'                  => 'user_id',
                            'referencedColumnName'  => 'id'
                        ],
                    ],
                    'inverseJoinColumns' => [
                        [
                            'name'                  => 'tenant_id',
                            'referencedColumnName'  => 'id'
                        ],
                    ]
                ]
            ]);
        }

        if ($metadata->getName() == $this->userClass) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->tenantClass,
                'fieldName'    => 'owner',
                'cascade'      => ['persist'],
                'joinColumn'   => [
                    'name'                 => 'owner_id',
                    'referencedColumnName' => 'id',
                    'nullable'             => true
                ]
            ]);
        }

        if (in_array('Cvele\MultiTenantBundle\Model\TenantAwareEntityInterface', class_implements($metadata->getName()))) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->tenantClass,
                'fieldName'    => 'tenant',
                'cascade'      => ['persist'],
                'joinColumn'   => [
                    'name'                 => 'tenant_id',
                    'referencedColumnName' => 'id',
                    'nullable'             => false
                ]
            ]);
        }

    }
}
