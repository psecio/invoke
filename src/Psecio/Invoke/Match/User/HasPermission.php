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
			if ($permissionName === $permission->getName()) {
				return true;
			}
		}
		return false;
	}
}