<?php

declare(strict_types=1);

namespace Timiki\Bundle\RpcClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rpc_client');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->variableNode('options')
                    ->defaultValue([])
                    ->info('RPC Client options.')
                ->end()
                ->variableNode('http_options')
                    ->defaultValue(
                        [
                            'verify' => false,
                        ]
                    )
                    ->info('Http client options.')
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
