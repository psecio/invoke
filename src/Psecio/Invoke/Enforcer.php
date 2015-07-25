<?php

namespace Psecio\Invoke;
use \Psecio\Invoke\Match as Match;
use \Psecio\Invoke\Resource as Resource;

class Enforcer
{
	private $config = array();
	private $fail = false;
	private $error = 'There was an error!';

	private $options = array(
		'protected' => 'resource.isProtected',
		'groups' => 'user.hasGroup',
		'permissions' => 'user.hasPermission',
		'methods' => 'resource.hasMethod',
		'params' => 'route.hasParameters',
		'callback' => 'object.callback'
	);

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
	 * Return the current config
	 *
	 * @return array Current configuration values
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Set the current configuration data
	 *
	 * @param array $config Configuration data
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Get the current error message
	 *
	 * @return string Error message
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Set the current error mesasge
	 *
	 * @param string $error Error message
	 */
	public function setError($error)
	{
		$this->error = $error;
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
				$routeInstance->setParams($match->getParams());
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
		$data = new Data($user, $resource);

		$config = $this->config;
		$uri = $resource->getUri(true)['path'];

		// See if we have a route match at all
		$route = $this->findRouteMatch($uri, $config);

		// If we don't have a configuration for the route, allow
		// 	public resource
		if ($route === null) {
			return true;
		}
		$data->setRoute($route);

		$config = $route->getConfig();

		foreach ($config as $index => $option) {
			if (isset($this->options[$index])) {
				$found = $this->options[$index];

				// make a match for this type
				$matches[] = Match::create($found, ['data' => $option]);
			}
		}

		foreach ($matches as $match) {
			$result = $match->evaluate($data);
			if ($result === false) {
				$this->setError($match->getError());
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