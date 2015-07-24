<?php

namespace Psecio\Invoke\Match\User;

class HasPermission extends \Psecio\Invoke\MatchInstance
{
	/**
	 * Evaluate the provided permission instance (or name) against provided
	 * 	for a match
	 *
	 * @param string|\Psecio\Invoke\PermissionInterface $data Permission data
	 * @return boolean Pass/fail status of evaluation
	 */
	public function evaluate($data)
	{
		$permissions = $this->getConfig('data');
		$userPermissions = $data->user->getPermissions();

		// If any are objects, transform to strings
		foreach ($userPermissions as $index => $permission) {
			$userPermissions[$index] = ($permission instanceof \Psecio\Invoke\PermissionInterface)
				? $permission->getName() : $permission;
		}

		// Find the intersection
		$match = array_intersect($permissions, $userPermissions);
		return (count($match) === count($permissions));
	}
}