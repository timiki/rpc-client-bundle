<?php

declare(strict_types=1);

namespace Timiki\Bundle\RpcClientBundle\DependencyInjection;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Timiki\Bundle\RpcClientBundle\RpcClientRegistry;
use Timiki\RpcClient\Client;
use Timiki\RpcClient\ClientInterface;

class RpcClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
         * Http client.
         */
        $httpClient = new Definition(HttpClient::class, [(array) $config['http_options']]);
        $httpClient->setPublic(true);

        $container->setDefinition('rpc.client.http', $httpClient);
        $container->setAlias(HttpClientInterface::class.' $rpcHttpClient', 'rpc.client.http');

        /**
         * Registry.
         */
        $registry = new Definition(RpcClientRegistry::class);
        $registry->setPublic(true);

        /**
         * Client.
         */
        $createClient = function (?string $name, string $address) use ($container, $registry, $config) {
            $serviceId = empty($name) ? 'rpc.client' : 'rpc.client.'.$name;
            $clientName = $name ?? 'rpc.client';

            if (empty($name)) {
                $varName = '$rpcClient';
            } else {
                $varName = '$'.lcfirst(str_replace(['.', '-', '_'], '', ucwords($name, '._-'))).'RpcClient';
            }

            $definition = new Definition(
                Client::class,
                [
                    $address,
                    $config['options'],
                    new Reference('rpc.client.http'),
                ]
            );

            $definition->addMethodCall('setEventDispatcher', [new Reference('event_dispatcher', ContainerInterface::NULL_ON_INVALID_REFERENCE)]);
            $definition->setPublic(true);

            $container->setDefinition($serviceId, $definition);
            $container->setAlias(ClientInterface::class.' '.$varName, $serviceId);

            $registry->addMethodCall('add', [$clientName, new Reference($serviceId)]);
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
