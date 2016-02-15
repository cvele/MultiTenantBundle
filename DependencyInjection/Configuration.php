<?php

namespace Cvele\MultiTenantBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cvele_multi_tenant');

        $rootNode
            ->children()
                ->scalarNode('user_entity_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('tenant_entity_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('logout_route')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('redirect_after_login_route')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('pick_tenant_route')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
