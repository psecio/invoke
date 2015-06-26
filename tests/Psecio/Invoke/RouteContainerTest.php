<?php

namespace Psecio\Invoke;

class RouteContainerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test that a valid container is created
	 */
	public function testCreateValidContainer()
	{
		$route = '/test';
		$config = ['name' => 'foo'];
		$container = new \Psecio\Invoke\RouteContainer($route, $config);
		$routeInstance = $container->getRoute();

		$this->assertTrue($routeInstance instanceof \Psecio\Invoke\MatchInstance);
		$this->assertEquals(
			['route' => '/test'],
			$routeInstance->getConfig()
		);
	}
}
