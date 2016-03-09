<?php

namespace Timiki\Bundle\RpcClientBundle;

use Timiki\RpcClientCommon\Client;
use Timiki\RpcClientCommon\Client\Response;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Client class
 */
class RpcClient extends Client
{
	/**
	 * Container
	 *
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * Create new client
	 *
	 * @param null|string|array $address
	 * @param array $options
	 * @param string $type
	 * @param string $locale
	 * @param null|ContainerInterface $container
	 */
	public function __construct($address = null, array $options = [], $type = 'json', $locale = 'en', ContainerInterface $container = null)
	{
		$this->setContainer($container);

		$headers = (array)$options['headers'];
		$cookies = (array)$options['cookies'];

		if (is_array($options['forwardHeaders']) and !empty($options['forwardHeaders'])) {
			foreach ($options['forwardHeaders'] as $header) {
				$headers[$header] = Request::createFromGlobals()->headers->get($header);
				if (strtolower($header) == 'client-ip') {
					$headers[$header] = [Request::createFromGlobals()->getClientIp()];
				}
			}
		}

		if (is_array($options['forwardCookies']) and !empty($options['forwardCookies'])) {
			foreach (Request::createFromGlobals()->cookies->all() as $name => $values) {
				if (in_array($name, $options['forwardCookies'])) {
					$cookies[$name] = $values;
				}
			}
		}

		parent::__construct($address, $type, $headers, $cookies, $locale);
	}

	/**
	 * Set container
	 *
	 * @param ContainerInterface|null $container
	 * @return $this
	 */
	public function setContainer($container)
	{
		if ($container instanceof ContainerInterface) {
			$this->container = $container;
		}

		return $this;
	}

	/**
	 * Get container
	 *
	 * @return ContainerInterface|null
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Call request
	 *
	 * @param string $method
	 * @param array $params
	 * @param array $extra
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
