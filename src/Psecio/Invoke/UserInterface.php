<?php

namespace Psecio\Invoke;

interface UserInterface
{
	/**
	 * Return the set of groups for the user
	 *
	 * @return array Set of \Psecio\Invoke\GroupInterface objects
	 */
	public function getGroups();

	/**
	 * Return the set of permissions for the user
	 *
	 * @return array Set of \Psecio\Invoke\PermissionInterface objects
	 */
	public function getPermissions();

	/**
	 * Check to ensure the user is authenticated
	 *
	 * @return boolean Result of authed/not authed check
	 */
	public function isAuthed();
}