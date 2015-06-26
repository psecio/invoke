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
		$permissionName = $this->getConfig('name');

		foreach ($data->getPermissions() as $permission) {
			$name = ($permission instanceof \Psecio\Invoke\PermissionInterface)
				? $permission->getName() : $permission;
			if ($permissionName === $name) {
				return true;
			}
		}
		return false;
	}
}