<?php

namespace Psecio\Invoke;

class Data
{
	/**
	 * Current user instance
	 * @var \Psecio\Invoke\UserInterface
	 */
	private $user;

	/**
	 * Current resource being accessed
	 * @var \Psecio\Invoke\Resource
	 */
	private $resource;

	/**
	 * Current route match for the request
	 * @var \Psecio\Invoke\RouteContainer
	 */
	private $route;

	/**
	 * Current enforcer object
	 * @var array
	 */
	private $enforcer;

	public function __construct(UserInterface $user, $resource, $route = null)
	{
		$this->user = $user;
		$this->resource = $resource;

		if ($route !== null) {
			$this->route = $route;
		}
	}

	public function __get($name)
	{
		$method = 'get'.ucwords(strtolower($name));
		if (method_exists($this, $method)) {
			return $this->$method();
		}
		return null;
	}

	/**
	 * Get the current user instance
	 *
	 * @return \Psecio\Invoke\InvokeUser instance
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Get the current route information
	 *
	 * @return \Psecio\Invoke\RouteContainer instance
	 */
	public function getRoute()
	{
		return $this->route;
	}

	/**
	 * Get the current resource instance
	 *
	 * @return \Psecio\Invoke\Resource instance
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * Set the current route instance
	 *
	 * @param \Psecio\Invoke\RouteContaine $route Instance
	 */
	public function setRoute(RouteContainer $route)
	{
		$this->route = $route;
	}

	/**
	 * Set the current enforcer object
	 *
	 * @param \Psecio\Invoke\Enforcer $enforcer Enforcer object
	 */
	public function setEnforcer(\Psecio\Invoke\Enforcer &$enforcer)
	{
		$this->enforcer = $enforcer;
	}

	/**
	 * Get the current enforcer object
	 *
	 * @return object Enforder object
	 */
	public function getEnforcer()
	{
		return $this->enforcer;
	}
}