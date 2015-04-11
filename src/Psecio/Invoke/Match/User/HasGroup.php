<?php

namespace Psecio\Invoke\Match\User;

class HasGroup implements \Psecio\Invoke\MatchInterface
{
	private $config;

	public function __construct(array $config = array(), $negate = false)
	{
		$this->config = $config;
	}

	public function evaluate($data)
	{
		$groupName = $this->config['name'];

		foreach ($data->getGroups() as $group) {
			if ($groupName === $group->getName()) {
				return true;
			}
		}
		return false;
	}
}