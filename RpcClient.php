<?php

namespace Timiki\Bundle\RpcClientBundle;

use Timiki\RpcClientCommon\Client;
use \Symfony\Component\DependencyInjection\Container;
use Timiki\RpcClientCommon\Client\Response;

/**
 * Client class
 */
class RpcClient extends Client
{
    /**
     * Container
     *
     * @var Container
     */
    protected $container;

    /**
     * Create new client
     *
     * @param null|string|array $address
     * @param array             $options
     * @param string            $type
     * @param string            $locale
     * @param null|Container    $container
     */
    public function __construct($address = null, array $options = [], $type = 'json', $locale = 'en', $container = null)
    {
        $this->setContainer($container);
        parent::__construct($address, $options, $type, $locale);
    }

    /**
     * Set container
     *
     * @param Container|null $container
     * @return $this
     */
    public function setContainer($container)
    {
        if ($container instanceof Container) {
            $this->container = $container;
        }

        return $this;
    }

    /**
     * Get container
     *
     * @return Container|null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Call request
     *
     * @param string $method
     * @param array  $params
     * @param array  $extra
     * @return Response
     */
    public function call($method, array $params = [], array $extra = [])
    {
        // Before run call need stop session
        if ($this->getContainer() !== null) {
            $this->getContainer()->get('session')->save();
        }

        // Call method
        $response = parent::call($method, $params, $extra);

        // After run call need restart session
        if ($this->getContainer() !== null) {
            $this->getContainer()->get('session')->migrate();
        }

        return $response;
    }
}
