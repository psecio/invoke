<?php

namespace Psecio\Invoke\Match\User;

class HasGroup extends \Psecio\Invoke\MatchInstance
{
	/**
	 * Evaluate the group (or name) provided to see if there's a match
	 *
	 * @param string|\Psecio\Invoke\GroupInterface $data Group name or instance
	 * @return boolean Pass/fail status of the evaluation
	 */
	public function evaluate($data)
	{
		$groupName = $this->getConfig('name');

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