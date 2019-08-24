<?php

namespace Timiki\Bundle\RpcClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Timiki\Bundle\RpcClientBundle\RpcClientRegistry;
use Timiki\RpcClient\Client;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RpcClientExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
         * Registry.
         */
        $registry = new Definition(RpcClientRegistry::class);
        $registry->setPublic(true);

        /**
         * Client.
         *
         * @param string       $name
         * @param array|string $address
         */
        $createClient = function ($name, $address) use ($container, $registry, $config) {
            $rpcClientId = empty($name) ? 'rpc.client' : 'rpc.client.'.$name;
            $definition = new Definition(
                Client::class,
                [
                    $address,
                    new Reference('event_dispatcher', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                    $config['options'],
                    $config['cache'] ? new Reference($config['cache'], ContainerInterface::NULL_ON_INVALID_REFERENCE) : null,
                ]
            );

            $definition->setPublic(true);
            $container->setDefinition($rpcClientId, $definition);
            $registry->addMethodCall('add', [$name, new Reference($rpcClientId)]);
        };

        if (\is_string($config['connection'])) {
            $createClient(null, $config['connection']);
        } elseif (\is_array($config['connection'])) {
            foreach ($config['connection'] as $key => $value) {
                $createClient($key, $value);
            }
        }

        $container->setDefinition(RpcClientRegistry::class, $registry);
        $container->setAlias('rpc.client.registry', RpcClientRegistry::class);
    }
}
