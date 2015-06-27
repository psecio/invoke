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
		return (isset($this->user->groups)) ? $this->user->groups : [];
	}
	public function getPermissions()
	{
		return (isset($this->user->permissions)) ? $this->user->permissions : [];
	}
	public function isAuthed()
	{
		return (isset($this->user->authed)) ? $this->user->authed : true;
	}
}