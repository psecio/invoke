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

	public function getUser()
	{
		return $this->user;
	}
	public function getRoute()
	{
		return $this->route;
	}
	public function getResource()
	{
		return $this->resource;
	}

	public function setRoute(RouteContainer $route)
	{
		$this->route = $route;
	}
}