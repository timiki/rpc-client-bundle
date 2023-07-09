<?php

declare(strict_types=1);

namespace Timiki\Bundle\RpcClientBundle;

use Timiki\RpcClient\ClientInterface;

class RpcClientRegistry
{
    /**
     * Rpc clients.
     *
     * @var ClientInterface[]
     */
    private array $clients = [];

    /**
     * Add rpc client by name.
     */
    public function add(string $name, ClientInterface $rpcClient): self
    {
        $this->clients[$name] = $rpcClient;

        return $this;
    }

    /**
     * Get rpc client by name.
     *
     * @throws \Exception
     */
    public function get(string $name): ClientInterface
    {
        if (!isset($this->clients[$name])) {
            throw new \Exception("Rpc client {$name} not found");
        }

        return $this->clients[$name];
    }

    /**
     * Is rpc client exist.
     */
    public function has(string $name): bool
    {
        return isset($this->clients[$name]);
    }
}
