<?php

namespace Psecio\Invoke;
use \Psecio\Invoke\Match as Match;
use \Psecio\Invoke\Resource as Resource;

class Enforcer
{
	private $config = array();
	private $fail = false;

	public function __construct($configPath)
	{
		$this->loadConfig($configPath);
	}

	/**
	 * Load the configuration from the given path
	 *
	 * @param string $configPath Path to YAML configuration file
	 * @return array Configuration data set
	 */
	public function loadConfig($configPath)
	{
		$yaml = new \Symfony\Component\Yaml\Parser();
	    $config = $yaml->parse(file_get_contents($configPath));

	    foreach ($config as $route => $setup) {
	        $this->config[$route] = new RouteContainer($route, $setup);
	    }
	    return $this->config;
	}

	/**
	 * Try to find a route match in the current config set
	 *
	 * @param string $uri URI to evaluate
	 * @param array $config Current route configuration
	 * @return \Psecio\Invoke\RouteContainer|null Route if found, null if not
	 */
	public function findRouteMatch($uri, array $config)
	{
		$route = null;
		foreach ($config as $matchUri => $routeInstance) {
			$match = Match::create('route.regex', ['route' => $matchUri]);
			if ($match->evaluate($uri) === true) {
				return $routeInstance;
			}
		}
		return $route;
	}

	/**
	 * Check to see if the request is authorized
	 * 	By default, fails closed
	 *
	 * @param \Psecio\Invoke\UserInterface $user User instance
	 * @param \Psecio\Invoke\Resource $resource Resource instance
	 * @param array $matches Additional matches to add manually for evaluation
	 * @return boolean Pass/fail of authorization
	 */
	public function isAuthorized(
		\Psecio\Invoke\UserInterface $user, \Psecio\Invoke\Resource $resource, array $matches = array()
	)
	{
		$config = $this->config;
		$uri = $resource->getUri();

		// See if we have a route match at all
		$route = $this->findRouteMatch($uri, $config);

		// If we don't have a configuration for the route, allow
		// 	public resource
		if ($route === null) {
			return true;
		}

		$config = $route->getConfig();

		// If it's either not marked as protected and if the user is logged in
		if ($this->isProtected($route) === true && !$user->isAuthed()) {
			return false;
		}

		// Now we set up the matches to evaluate
		if (isset($config['groups'])) {
			foreach ($config['groups'] as $group) {
				$matches[] = Match::create('user.hasGroup', ['name' => $group]);
			}
		}

		// And check permissions
		if (isset($config['permissions'])) {
			foreach ($config['permissions'] as $permission) {
				$matches[] = Match::create('user.hasPermission', ['name' => $permission]);
			}
		}

		// And methods
		if (isset($config['methods'])) {
			foreach ($config['methods'] as $httpMethod) {
				echo $httpMethod;
				$matches[] = Match::create('resource.hasMethod', ['method' => $httpMethod]);
			}
		}

		foreach ($matches as $match) {
			switch($this->getMatchType($match)) {
				case 'route':
				case 'resource':
					$result = $match->evaluate($resource);
					break;
				case 'user':
					$result = $match->evaluate($user);
					break;
			}

			// If there's a failure, return!
			if ($result === false) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the match type of the given object instance
	 *
	 * @param object $match Match object instance
	 * @return string Match type
	 */
	public function getMatchType($match)
	{
		$ns = explode('\\', get_class($match));
		return strtolower($ns[3]);
	}

	/**
	 * Check to ensure the route exists in the current configuration
	 * 	One to one string match, not a regex match
	 *
	 * @param string $route Route to match
	 * @return boolean Match found/no match
	 */
	public function routeExists($route)
	{
		return array_key_exists($route, $this->config);
	}

	/**
	 * Evaluate if the endpoint has protection turned on
	 *
	 * @param \Psecio\RouteContainer $route Route instance
	 * @return boolean On/off status of protection
	 */
	public function isProtected($route)
	{
		$config = $route->getConfig();
		return (array_key_exists('protected', $config) && $config['protected'] === 'on');
	}
}