<?php

namespace Psecio\Invoke\Match\Resource;

class HasMethod extends \Psecio\Invoke\MatchInstance
{
	protected $error = 'Mismatch on HTTP method :data';

	/**
	 * Evaluate the provided resource to see if it's in the allowed
	 * 	set of HTTP methods
	 *
	 * @param \Psecio\Invoke\Resource $data Resource instance
	 * @return boolean Pass/fail status
	 */
	public function evaluate($data)
	{
		$resource = $data->resource;
		$httpMethod = ($resource instanceof \Psecio\Invoke\Resource)
			? $resource->getHttpMethod() : $resource;

		return (in_array(strtolower($httpMethod), $this->getConfig('data')));
	}
}