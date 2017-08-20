<?php

namespace Timiki\Bundle\RpcClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Timiki\RpcClient\Client;

/**
 * This is the class that loads and manages your bundle configuration
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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        /**
         * Client
         *
         * @param $name
         * @param $address
         */
        $createClient = function ($name, $address) use ($container) {
            $container->setDefinition(
                empty($name) ? 'rpc.client' : 'rpc.client.'.$name,
                new Definition(
                    Client::class,
                    [
                        $address,
                        new Reference('rpc.client.event_dispatcher'),
                    ]
                )
            );
        };

        $defaultAddress = null;

        foreach ((array)$config['connection'] as $key => $value) {
            if (is_numeric($key)) {
                $createClient($value['name'], $value['address']);
            } elseif ($key === 'address') {
                $defaultAddress = $value;
            }
        }

        if (!empty($defaultAddress)) {
            $createClient(null, $defaultAddress);
        }
    }
}
