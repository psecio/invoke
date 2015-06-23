<?php

namespace Psecio\Invoke\Match\User;

class HasPermission implements \Psecio\Invoke\MatchInterface
{
	private $config;

	public function __construct(array $config = array(), $negate = false)
	{
		$this->config = $config;
	}

	public function evaluate($data)
	{
		$permissionName = $this->config['name'];

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