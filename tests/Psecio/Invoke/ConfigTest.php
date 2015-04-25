<?php

namespace Psecio\Invoke;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	private $config;
	private $path = '../../sample.config.yml';

	public function setUp()
	{
		$this->config = new Config(__DIR__.'/'.$this->path);
	}
	public function tearDown()
	{
		unset($this->config);
	}

	/**
	 * Test the get/set of the path to the configuration file (yal)
	 */
	public function testSetPathOnConstruct()
	{
		$path = __DIR__.'/'.$this->path;
		$config = new Config($path);

		$this->assertEquals($path, $config->getPath());
	}

	/**
	 * Test that an exception is thrown when the configuration
	 * 	file path is invalid
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidFilePath()
	{
		$config = new Config('.');
	}

	/**
	 * Test the fetching of the entire configuration
	 * 	only checks the top level
	 */
	public function testGetConfigAll()
	{
		$path = __DIR__.'/'.$this->path;
		$config = new Config($path);

		$result = $config->getConfig();
		$this->assertTrue(isset($result['event/add']));
	}

	/**
	 * Test the fetch of just one configuration key
	 */
	public function testGetConfigOneKey()
	{
		$path = __DIR__.'/'.$this->path;
		$config = new Config($path);

		$result = $config->getConfig('event/add');
		$this->assertTrue($result instanceof \Psecio\Invoke\RouteContainer);
	}
}