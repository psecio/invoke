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
	 * Check to see if the request is authorized
	 * 	By default, fails closed
	 *
	 * @param  \Psecio\Invoke\UserInterface $user     [description]
	 * @param  \Psecio\Invoke\Resource      $resource [description]
	 * @param  [type]                       $match    [description]
	 * @return boolean                                [description]
	 */
	public function isAuthorized(
		\Psecio\Invoke\UserInterface $user, \Psecio\Invoke\Resource $resource, array $matches = array()
	)
	{
		$config = $this->config;
		$uri = $resource->getUri();
		$uri = (strlen($uri) > 1) ? substr($uri, 1) : $uri;

		// See if we have a route match
		$match = \Psecio\Invoke\Match::create('route.regex', ['route' => $uri]);
		if ($match->evaluate($uri) === false) {
			return $this->fail;
		}

		// If we don't have a configuration for the rotue, allow
		if (!isset($config[$uri])) {
			return true;
		}

		$route = $config[$uri];
		$config = $route->getConfig();

		// If it's either not marked as protected and if the user is logged in
		if ($this->isProtected($config) === true && !$user->isAuthed()) {
			return false;
		}

		// Now we set up the matches to evaluate
		if (isset($config['groups'])) {
			foreach ($config['groups'] as $group) {
				$matches[] = \Psecio\Invoke\Match::create('user.hasGroup', ['name' => $group]);
			}
		}

		// And check permissions
		if (isset($config['permissions'])) {
			foreach ($config['permissions'] as $permission) {
				$matches[] = \Psecio\Invoke\Match::create('user.hasPermission', ['name' => $permission]);
			}
		}

		foreach ($matches as $match) {
			switch($this->getMatchType($match)) {
				case 'route':
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

	public function getMatchType($match)
	{
		$ns = explode('\\', get_class($match));
		return strtolower($ns[3]);
	}

	public function routeExists($route)
	{
		return array_key_exists($route, $this->config);
	}

	public function isProtected($config)
	{
		return (array_key_exists('protected', $config) && $config['protected'] === 'on');
	}
}