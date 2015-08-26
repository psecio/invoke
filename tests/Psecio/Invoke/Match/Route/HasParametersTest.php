<?php

namespace Psecio\Invoke\Match\Route;

class HasParametersTest extends \PHPUnit_Framework_TestCase
{
	private $match;

	public function setUp()
	{
		$this->match = new Regex();

		$_SERVER['REQUEST_METHOD'] = 'GET';

		$user = new \Psecio\Invoke\TestUser(['username' => 'testuser1']);
		$resource = new \Psecio\Invoke\Resource('/foo/bar');
		$route = new \Psecio\Invoke\RouteContainer('/foo/bar', []);

		$this->data = new \Psecio\Invoke\Data($user, $resource, $route);
	}
	public function tearDown()
	{
		unset($this->match);
	}

	/**
	 * Test the valid match on a set of parameters
	 */
	public function testValidParameterMatch()
	{
		$data = ['foo' => 'test1'];
		$config = ['data' => [$data]];

		$route = new \Psecio\Invoke\RouteContainer('/foo/bar', []);
		$route->setParams($data);
		$this->data->setRoute($route);

		$hasParam = new HasParameters($config);
		$this->assertTrue($hasParam->evaluate($this->data));
	}

	/**
	 * Test that an invalid match is triggered when the value
	 * 	does not match
	 */
	public function testInvalidValueParameterMatch()
	{
		$data = ['foo' => 'test1'];
		$config = ['data' => [$data]];

		$route = new \Psecio\Invoke\RouteContainer('/foo/bar', []);
		$route->setParams(['foo' => 'test2']);
		$this->data->setRoute($route);

		$hasParam = new HasParameters($config);
		$this->assertFalse($hasParam->evaluate($this->data));
	}

	/**
	 * Test that an invalid match is triggered when the value
	 * 	does not exist
	 */
	public function testMissingValueParameterMatch()
	{
		$config = ['data' => [[]]];

		$route = new \Psecio\Invoke\RouteContainer('/foo/bar', []);
		$route->setParams(['foo' => 'test2']);
		$this->data->setRoute($route);

		$hasParam = new HasParameters($config);
		$this->assertFalse($hasParam->evaluate($this->data));
	}
}