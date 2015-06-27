<?php

namespace Psecio\Invoke;

class MatchTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test that a match instance is created correctly for a
	 * 	valid type
	 */
	public function testCreateValidMatchInstance()
	{
		$instance = Match::create('user.hasGroup', ['group' => 'test']);
		$this->assertTrue($instance instanceof \Psecio\Invoke\MatchInstance);
	}

	/**
	 * Test that an exception is thrown when a bad match type
	 * 	is provided
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateInvalidMatchInstance()
	{
		$instance = Match::create('bad.matchType', []);
	}

	/**
	 * Test that the namespace for a match type is formatted correctly
	 */
	public function testFormatMatchNamespace()
	{
		$result = Match::formatNamespace('user.hasGroup');
		$this->assertEquals(
			'\Psecio\Invoke\Match\User\HasGroup',
			$result
		);
	}
}
