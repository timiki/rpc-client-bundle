<?php

namespace Timiki\Bundle\RpcClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $treeBuilder = new TreeBuilder('rpc_client');
        $rootNode = $treeBuilder->getRootNode();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('cache')
                    ->defaultValue(null)
                    ->info('Id cache service. Cache service must be instance of "Doctrine\Common\Cache\Cache".')
                ->end()
                ->variableNode('options')
                    ->defaultValue([])
                    ->info('RPC Client options.')
                ->end()
                ->variableNode('connection')
                    ->info('Connection params or connect params list.')
                    ->defaultValue(null)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
