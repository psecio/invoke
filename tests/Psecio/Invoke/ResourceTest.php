<?php

namespace Psecio\Invoke;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
	private $serverBackup;

	public function setUp()
	{
		$this->serverBackup = $_SERVER;
	}
	public function tearDown()
	{
		$_SERVER = $this->serverBackup;
	}

	/**
	 * Test that the request URI is set correctly from the
	 * 	$_SERVER superglobal value
	 */
	public function testResourceSetUriFromRequest()
	{
		$uri = '/test';
		$_SERVER['REQUEST_URI'] = $uri;
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$resource = new Resource();
		$this->assertEquals($uri, $resource->getUri());
	}

	/**
	 * Test the setting of the resource URI manually
	 */
	public function testResourceSetUriManual()
	{
		$uri = '/test';
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$resource = new Resource($uri);
		$this->assertEquals($uri, $resource->getUri());
	}

	/**
	 * Test the setting of the HTTP method on the resource
	 * 	from the current request
	 */
	public function testHttpMethodSetFromRequest()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$resource = new Resource('/test');
		$this->assertEquals('GET', $resource->getHttpMethod());
	}

	/**
	 * Test the setting of the HTTP method manually on the resource
	 */
	public function testHttpMethodSetManual()
	{
		$method = 'GET';

		$resource = new Resource('/test', $method);
		$this->assertEquals($method, $resource->getHttpMethod());
	}
}