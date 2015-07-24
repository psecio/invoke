<?php

namespace Psecio\Invoke\Match\Route;

class HasParameters extends \Psecio\Invoke\MatchInstance
{
	protected $error = 'Mismatch on parameters';

	/**
	 * Evaluate the parameters on the current route to
	 * 	ensure there's a correct match
	 *
	 * @param array $data Set of context items (user, resource, route)
	 * @return boolean Pass/fail status
	 */
	public function evaluate($data)
	{
		$params = $data->route->getParams();
		$config = $this->getConfig();

		// Ensure all params match exactly
		$intersect = array_intersect($config['data'][0], $params);
		return (count($intersect) === count($params));
	}
}