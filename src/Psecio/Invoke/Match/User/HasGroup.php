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
			$name = ($group instanceof \Psecio\Invoke\GroupInterface)
				? $group->getName() : $group;
			if ($groupName === $name) {
				return true;
			}
		}
		return false;
	}
}