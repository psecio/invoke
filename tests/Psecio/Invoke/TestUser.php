<?php

namespace Psecio\Invoke;

class TestUser implements \Psecio\Invoke\UserInterface
{
	public function __construct($user)
	{
		$this->user = $user;
	}
	public function getGroups()
	{
		return [];
	}
	public function getPermissions()
	{
		return [];
	}
	public function isAuthed()
	{
		return true;
	}
}