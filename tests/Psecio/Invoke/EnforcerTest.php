<?php

namespace Psecio\Invoke;

require_once 'TestMatch.php';
require_once 'TestUser.php';

class EnforcerTest extends \PHPUnit_Framework_TestCase
{
	private $configPath = 'sample.config.yml';
	private $enforcer;

	public function setUp()
	{
		$this->enforcer = new Enforcer(__DIR__.'/../../'.$this->configPath);
	}

	private function setEnforcerConfig(array $config)
	{
		$this->enforcer->setConfig([
			'test/path' => new RouteContainer('test/path', $config)
		]);
	}

	/**
	 * Test to be sure the configuration is loaded correctly
	 * 	when the object is created
	 */
	public function testLoadConfigOnInit()
	{
		$config = $this->enforcer->getConfig();

		$this->assertTrue(is_array($config));
		$this->assertTrue(isset($config['event/add']));
	}

	/**
	 * Test that a valid route match is found when the URIs match
	 */
	public function testFindRouteMatchValid()
	{
		$uri = '/test';
		$route = new RouteContainer('/test', ['protected' => 'on']);

		$config = ['/test' => $route];
		$match = $this->enforcer->findRouteMatch($uri, $config);

		$this->assertTrue($match !== null);
		$this->assertTrue($match instanceof \Psecio\Invoke\RouteContainer);
	}

	/**
	 * Test that a null is returned when there's no route match
	 */
	public function testFindRouteMatchInvalid()
	{
		$uri = '/nomatch';
		$route = new RouteContainer('/test', ['protected' => 'on']);

		$config = ['/test' => $route];
		$match = $this->enforcer->findRouteMatch($uri, $config);

		$this->assertNull($match);
	}

	/**
	 * Test the filtering out of the match type
	 */
	public function testGetMatchTypeValid()
	{
		$match = new Match\TestMatch();
		$result = $this->enforcer->getMatchType($match);

		$this->assertEquals('testmatch', $result);
	}

	/**
	 * Test that finding route match in the set returns true
	 */
	public function testRouteExistsValid()
	{
		$match = 'event/add';
		$this->assertTrue($this->enforcer->routeExists($match));
	}

	/**
	 * Test that finding a route that doesn't exist
	 * 	returns a false
	 */
	public function testRouteExistsInvalid()
	{
		$match = 'bad/route';
		$this->assertFalse($this->enforcer->routeExists($match));
	}

	/**
	 * Test checking that a route is protected
	 */
	public function testRouteIsProtected()
	{
		$uri = '/test';
		$route = new RouteContainer('/test', ['protected' => 'on']);

		$config = ['/test' => $route];
		$match = $this->enforcer->findRouteMatch($uri, $config);

		$this->assertTrue($this->enforcer->isProtected($match));
	}

	/**
	 * Test that a true is returned when all criteria match on
	 * 	user, resource and config
	 */
	public function testIsAuthorizedValid()
	{
		$u = new \stdClass();
		$u->groups = ['group1', 'test'];
		$u->authed = true;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		// Set our configuration
		$this->setEnforcerConfig(['protected' => 'on', 'groups' => ['test']]);
		$this->assertTrue($this->enforcer->isAuthorized($user, $resource));
	}

	/**
	 * Test that a public resource fails open
	 */
	public function testIsAuthorizedPublic()
	{
		$u = new \stdClass();
		$u->groups = ['group1', 'test'];
		$u->authed = true;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		$this->assertTrue($this->enforcer->isAuthorized($user, $resource));
	}

	/**
	 * Test that whan a route is protected and a user is not
	 * 	authenticated, it returns false
	 */
	public function testIsAuthorizedNoAuth()
	{
		$u = new \stdClass();
		$u->authed = false;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		// Set our configuration
		$this->setEnforcerConfig(['protected' => 'on']);
		$this->assertFalse($this->enforcer->isAuthorized($user, $resource));
	}

	/**
	 * Test that the user is allowed (true) when the user has the required
	 * 	permissions
	 */
	public function testIsAuthorizedHasPermissions()
	{
		$u = new \stdClass();
		$u->permissions = ['perm1'];
		$u->authed = true;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		// Set our configuration
		$this->setEnforcerConfig(['protected' => 'on', 'permissions' => ['perm1']]);
		$this->assertTrue($this->enforcer->isAuthorized($user, $resource));
	}

	/**
	 * Test the evaluation of methods, allowing because the Resource
	 * 	is GET and the "methods" list includes "get"
	 */
	public function testIsAuthorizedHasMethods()
	{
		$u = new \stdClass();
		$u->authed = true;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		// Set our configuration
		$this->setEnforcerConfig(['protected' => 'on', 'methods' => ['get']]);
		$this->assertTrue($this->enforcer->isAuthorized($user, $resource));
	}

	/**
	 * Test a failure where it has criteria to check (besides auth)
	 * 	and returns a failure
	 */
	public function testIsAuthorizedFailure()
	{
		$u = new \stdClass();
		$u->authed = true;

		$user = new TestUser($u);
		$resource = new Resource('test/path', 'GET');

		// Set our configuration
		$this->setEnforcerConfig(['protected' => 'on', 'methods' => ['post']]);
		$this->assertFalse($this->enforcer->isAuthorized($user, $resource));
	}
}
