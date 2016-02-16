<?php

namespace Cvele\MultiTenantBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MultiTenantExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('multi_tenant.logout_route', $config['logout_route']);
        $container->setParameter('multi_tenant.redirect_after_login_route', $config['redirect_after_login_route']);
        $container->setParameter('multi_tenant.pick_tenant_route', $config['pick_tenant_route']);
        $container->setParameter('multi_tenant.user_entity', $config['user_entity_class']);
        $container->setParameter('multi_tenant.tenant_entity', $config['tenant_entity_class']);
    }
}
