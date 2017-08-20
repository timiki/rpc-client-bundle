<?php

namespace Timiki\Bundle\RpcClientBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RpcClientBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new RegisterListenersPass(
                'rpc.client.event_dispatcher',
                'rpc.client.event_listener',
                'rpc.client.event_subscriber'
            )
        );
    }
}
