<?php

namespace Psecio\Invoke\Match\Object;

class Callback extends \Psecio\Invoke\MatchInstance
{
	protected $error = 'Failure returned from callback :data';

	/**
	 * Execute the provided callback
	 *
	 * @param \Psecio\Invoke\Data $data Data object instance
	 * @return boolean Result of evaluation
	 */
	public function evaluate($data)
	{
		$config = $data->route->getConfig();

		// Break up the class and method
		list($class, $method) = explode('::', $config['callback']);
		if (!class_exists($class)) {
			throw new \InvalidArgumentException('Class "'.$class.'" does not exist');
		}
		return $class::$method($data);
	}
}