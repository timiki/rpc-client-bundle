<?php

namespace Timiki\Bundle\RpcClientBundle;

use Timiki\RpcClient\Client;

class RpcClientRegistry
{
    /**
     * Rpc clients.
     *
     * @var Client[]
     */
    private $clients = [];

    /**
     * Add rpc client by name.
     *
     * @param string $name
     * @param Client $rpcClient
     */
    public function add($name, Client $rpcClient)
    {
        $this->clients[$name] = $rpcClient;
    }

    /**
     * Get rpc client by name.
     *
     * @param string $name
     *
     * @throws \Exception
     *
     * @return Client
     */
    public function get($name)
    {
        if (!isset($this->clients[$name])) {
            throw new \Exception("Rpc client {$name} not found");
        }

        return $this->clients[$name];
    }

    /**
     * Is rpc client exist.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->clients[$name]);
    }
}
