<?php

namespace Psecio\Invoke\Match\Object;

class CallbackTest extends \PHPUnit_Framework_TestCase
{
	private $data;

	public function setUp()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$user = new \Psecio\Invoke\TestUser(['username' => 'testuser1']);
		$resource = new \Psecio\Invoke\Resource('/foo/bar');
		$route = new \Psecio\Invoke\RouteContainer(
			'/foo/bar',
			['callback' => '\Psecio\Invoke\Match\Object\CallbackMock::test1']
		);

		$this->data = new \Psecio\Invoke\Data($user, $resource, $route);
	}
	public function tearDown()
	{
		unset($this->data);
	}

	/**
	 * Test a valid callback match
	 */
	public function testValidCallbackMatch()
	{
		$config = ['callback' => 'CallbackMock::test1'];

		$cb = new Callback($config);
		$this->assertEquals('test1', $cb->evaluate($this->data));
	}

	/**
	 * Test that an exception is thrown when an invalid class is given
	 *
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidCallbackMatch()
	{
		$route = new \Psecio\Invoke\RouteContainer(
			'/foo/bar',
			['callback' => 'BadClass::test1']
		);
		$this->data->setRoute($route);

		$cb = new Callback([]);
		$cb->evaluate($this->data);
	}
}

class CallbackMock
{
	public static function test1()
	{
		return 'test1';
	}
}