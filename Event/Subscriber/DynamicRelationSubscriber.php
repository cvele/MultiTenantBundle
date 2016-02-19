<?php

namespace Cvele\MultiTenantBundle\Event\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

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
        if ($metadata->getName() == $this->userClass) {
            $metadata->mapManyToMany([
                'targetEntity'  => $this->tenantClass,
								'type'					=> ClassMetadataInfo::MANY_TO_MANY,
                'fieldName'     => 'userTenants',
								'inversedBy' 		=> 'users',
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

        if ($metadata->getName() == $this->tenantClass) {
            $metadata->mapManyToOne([
                'targetEntity' => $this->userClass,
                'fieldName'    => 'owner',
                'cascade'      => ['persist'],
                'joinColumn'   => [
                    'name'                 => 'owner_id',
                    'referencedColumnName' => 'id',
                    'nullable'             => true
                ]
            ]);

						$metadata->mapManyToMany([
								'targetEntity'  => $this->userClass,
								'mappedBy'      => 'userTenants',
								'fieldName' 		=> 'users'
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
