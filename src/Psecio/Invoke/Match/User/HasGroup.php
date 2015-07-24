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
		$groups = $this->getConfig('data');
		$userGroups = $data->user->getGroups();

		// If any are objects, transform to strings
		foreach ($userGroups as $index => $group) {
			$userGroups[$index] = ($group instanceof \Psecio\Invoke\GroupInterface)
				? $group->getName() : $group;
		}

		// Find the intersection
		$match = array_intersect($groups, $userGroups);
		return (count($match) === count($groups));
	}
}