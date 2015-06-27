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
}
