<?php

namespace Psecio\Invoke;

interface UserInterface
{
	public function getGroups();
	public function getPermissions();
	public function isAuthed();
}