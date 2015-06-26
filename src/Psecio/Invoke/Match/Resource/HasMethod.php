<?php

namespace Psecio\Invoke\Match\Resource;

class HasMethod extends \Psecio\Invoke\MatchInstance
{
	/**
	 * Evaluate the provided resource to see if it's in the allowed
	 * 	set of HTTP methods
	 *
	 * @param \Psecio\Invoke\Resource $data Resource instance
	 * @return boolean Pass/fail status
	 */
	public function evaluate($data)
	{
		$httpMethod = ($data instanceof \Psecio\Invoke\Resource)
			? $data->getHttpMethod() : $data;

		return (strtoupper($httpMethod) === strtoupper($this->getConfig('method')));
	}
}