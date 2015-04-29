<?php

namespace Psecio\Invoke\Match\Route;

class RegexTest extends \PHPUnit_Framework_TestCase
{
	private $match;

	public function setUp()
	{
		$this->match = new Regex();
	}
	public function tearDown()
	{
		unset($this->match);
	}

	/**
	 * Test a simple one to one match
	 */
	public function testSimpleValidMatch()
	{
		$config = array('route' => '/foo/bar');
		$regex = new Regex($config);

		$this->assertTrue($regex->evaluate('/foo/bar'));
	}

	/**
	 * Test the match against a Resource instance
	 */
	public function testValidMatchResource()
	{
		$resource = new \Psecio\Invoke\Resource('/foo/bar');
		$config = array('route' => '/foo/bar');
		$regex = new Regex($config);

		$this->assertTrue($regex->evaluate($resource));
	}

	/**
	 * Test a match where we can get the parameters (from the regex)
	 */
	public function testValidMatchParamaters()
	{
		$config = array('route' => '/foo/bar/(.+)');
		$regex = new Regex($config);

		$this->assertTrue($regex->evaluate('/foo/bar/baz'));
		$params = $regex->getParams();

		$this->assertEquals(
			$params,
			array('/foo/bar/baz', 'baz')
		);
	}
}