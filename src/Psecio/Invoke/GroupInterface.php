<?php

namespace Psecio\Invoke;

interface GroupInterface
{
	/**
	 * Get the "name" value for the group
	 *
	 * @return string Group name
	 */
	public function getName();

	/**
	 * Get the set of permissions for the group
	 *
	 * @return array Set of \Psecio\Invoke\PermissionInterface objects
	 */
	public function getPermissions();
}