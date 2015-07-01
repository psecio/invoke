<?php

namespace Psecio\Invoke\Match\Resource;

class IsProtected extends \Psecio\Invoke\MatchInstance
{
	/**
	 * Evaluate the provided resource to see if it's marked as protected
	 *
	 * @param \Psecio\Invoke\Resource $data Resource instance
	 * @return boolean Pass/fail status
	 */
	public function evaluate($data)
	{
		$setting = $this->getConfig('data');
		$user = $data['user'];

		return (strtolower($setting) === 'on' && $user->isAuthed());
	}
}